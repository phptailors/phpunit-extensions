<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\InternalErrorException;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorInterface;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorStackItemInterface;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorUtils;
use Tailors\PHPUnit\ResultFactory\ResultFactoryInterface;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type StackItem RecursiveResultFactoryStackItem
 *
 * @template-implements RecursiveVisitorInterface<RecursiveResultFactoryStackItem>
 */
final class RecursiveResultFactoryVisitor implements RecursiveVisitorInterface
{
    /**
     * @var bool
     *
     * @psalm-readonly
     */
    private $actual;

    /**
     * @var mixed
     */
    private $subject;

    /**
     * @var mixed
     */
    private $result;

    /**
     * @var ?SubjectResultCouple
     */
    private $subjectResultCouple;

    /**
     * @param mixed $subject
     */
    public function __construct(bool $actual, $subject)
    {
        $this->actual = $actual;
        $this->subject = $subject;
        $this->subjectResultCouple = null;
    }

    /**
     * @return mixed
     *
     * @psalm-mutation-free
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * @param array|\Traversable $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function enter($node, array $stack): bool
    {
        if (!$this->selectSubject($stack, $subject)) {
            return false;
        }

        return $this->enterIfSpecYieldsArray($node, $subject);
    }

    /**
     * @param array|\Traversable $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function leave($node, array $stack, bool $iterating): void
    {
        if (!$iterating) {
            return;
        }

        if (null === $this->subjectResultCouple) {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace('$this->subjectResultCouple is null');
        }

        $this->set($stack, $this->subjectResultCouple->result);

        $this->subjectResultCouple = null;
    }

    /**
     * @param mixed $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function visit($node, array $stack, bool $iterating): void
    {
        if (!$this->selectSubject($stack, $subject)) {
            return;
        }

        if ($node instanceof ResultFactoryWrapperInterface) {
            $factory = $node->getResultFactory();

            if ($factory->supports($subject)) {
                $result = $factory->getResult($this->actual, $subject);
            } else {
                $result = $subject;
            }
        } else {
            $result = $subject;
        }

        $this->set($stack, $result);
    }

    /**
     * @param array|\Traversable $node
     *
     * @throws CircularDependencyException
     *
     * @psalm-param list<StackItem> $stack
     */
    public function cycle($node, array $stack): bool
    {
        self::throwCircular($stack);
    }

    /**
     * @param array|\Traversable $node
     * @param mixed                 $key
     *
     * @psalm-param array-key       $key
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem($node, $key, array $stack): RecursiveVisitorStackItemInterface
    {
        if (null === $this->subjectResultCouple) {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace('$this->subjectResultCouple is null');
        }

        return new RecursiveResultFactoryStackItem($node, $key, $this->subjectResultCouple);
    }

    /**
     * @psalm-param StackItem       $item
     * @psalm-param list<StackItem> $stack
     */
    public function freeStackItem(RecursiveVisitorStackItemInterface $item, array $stack): void
    {
        $this->subjectResultCouple = $item->subjectResultCouple();
    }

    /**
     * @return never
     *
     * @throws CircularDependencyException
     *
     * @psalm-param list<StackItem> $stack
     */
    private static function throwCircular(array $stack): void
    {
        $pathString = RecursiveVisitorUtils::pathAsString($stack);

        throw new CircularDependencyException("Circular dependency found in nested array at \$array{$pathString}.");
    }

    /**
     * @param array|\Traversable $spec
     * @param mixed $subject
     *
     * @psalm-template SupportedSubject
     * @psalm-template SupportedInput
     *
     * @psalm-param ValueSelectorWrapperInterface<SupportedSubject>|ResultFactoryWrapperInterface<SupportedInput>|array|mixed $subject
     * @psalm-assert-if-true ValueSelectorWrapperInterface<SupportedSubject>|ResultFactoryWrapperInterface<SupportedInput>|array $subject
     */
    private function enterIfSpecYieldsArray($spec, $subject): bool
    {
        if ($spec instanceof ValueSelectorWrapperInterface) {
            $valueSelector = $spec->getValueSelector();

            if (!$valueSelector->supports($subject)) {
                return false;
            }

            if (!$spec instanceof ResultFactoryWrapperInterface) {
                return false;
            }

            $factory = $spec->getResultFactory();

            return $this->enterIfFactoryYieldsArray($factory, []);
        }

        if ($spec instanceof ResultFactoryWrapperInterface) {
            $factory = $spec->getResultFactory();

            return $this->enterIfFactoryYieldsArray($factory, $subject);
        }

        if (!is_array($spec) || !is_array($subject)) {
            return false;
        }

        $this->subjectResultCouple = new SubjectResultCouple($subject, $subject);

        return true;
    }

    /**
     * @param mixed $input
     *
     * @psalm-template SupportedInput
     *
     * @psalm-param ResultFactoryInterface<SupportedInput> $factory
     *
     * @psalm-assert-if-true SupportedInput $input
     */
    private function enterIfFactoryYieldsArray(ResultFactoryInterface $factory, $input): bool
    {
        if (!$factory->supports($input)) {
            return false;
        }

        $result = $factory->getResult($this->actual, $input);

        if (!$result instanceof \ArrayAccess) {
            return false;
        }

        $this->subjectResultCouple = new SubjectResultCouple($input, $result);

        return true;
    }

    /**
     * @param mixed $subject
     *
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-param-out mixed $subject
     */
    private function selectSubject(array $stack, &$subject): bool
    {
        if (0 === ($count = count($stack))) {
            $subject = $this->subject;
            return true;
        }

        $top = $stack[$count - 1];

        $key = $top->key();
        $parentNode = $top->node();
        $parentSubject = $top->subjectResultCouple->subject;

        if ($parentNode instanceof ValueSelectorWrapperInterface) {
            $valueSelector = $parentNode->getValueSelector();
            if (!$valueSelector->supports($parentSubject)) {
                return false;
            }

            return $valueSelector->select($parentSubject, $key, $subject);
        }

        if ($parentSubject instanceof \ArrayAccess) {
            if (!$parentSubject->offsetExists($key)) {
                return false;
            }

            $subject = $parentSubject->offsetGet($key);

            return true;
        }

        if (is_array($parentSubject) && array_key_exists($key, $parentSubject)) {
            $subject = $parentSubject[$key];

            return true;
        }

        return false;
    }

    /**
     * @param mixed $result
     *
     * @psalm-param list<StackItem> $stack
     */
    private function set(array $stack, $result): void
    {
        if (0 === ($count = count($stack))) {
            $this->result = $result;
            return;
        }

        $stack[$count - 1]->set($result);
    }
}

// vim: syntax=php sw=4 ts=4 et:
