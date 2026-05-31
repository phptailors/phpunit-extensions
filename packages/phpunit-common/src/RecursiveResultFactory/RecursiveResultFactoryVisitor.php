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
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorStackItemInterface;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorUtils;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type StackItem = RecursiveResultFactoryStackItem
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
final class RecursiveResultFactoryVisitor implements RecursiveResultFactoryVisitorInterface
{
    /**
     * @var ?bool
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

    public function __construct()
    {
        $this->actual = null;
        $this->subject = null;
        $this->subjectResultCouple = null;
    }

    /**
     * @param mixed $subject
     *
     * @psalm-assert bool $this->actual
     */
    public function begin(bool $actual, $subject): void
    {
        $this->actual = $actual;
        $this->subject = $subject;
        $this->result = null;
        $this->subjectResultCouple = null;
    }

    public function end(): void
    {
        $this->actual = null;
        $this->subject = null;
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
     * @psalm-param ArrayLike       $node
     * @psalm-param list<StackItem> $stack
     */
    public function enter(iterable $node, array $stack): bool
    {
        if (!$this->selectSubject($stack, $subject)) {
            return false;
        }

        return $this->enterIfSpecYieldsAnArray($node, $subject);
    }

    /**
     * @psalm-param ArrayLike       $node
     * @psalm-param list<StackItem> $stack
     */
    public function leave(iterable $node, array $stack, bool $iterating): void
    {
        if (!$iterating) {
            return;
        }

        if (null === $this->subjectResultCouple) {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace('$this->subjectResultCouple is null');
        }

        $this->set($stack, $this->subjectResultCouple->result);
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

        $this->set($stack, $subject);
    }

    /**
     * @throws CircularDependencyException
     *
     * @psalm-param ArrayLike       $node
     * @psalm-param list<StackItem> $stack
     */
    public function cycle(iterable $node, array $stack): bool
    {
        self::throwCircular($stack);
    }

    /**
     * @param mixed $key
     *
     * @psalm-param ArrayLike       $node
     * @psalm-param array-key       $key
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem(iterable $node, $key, array $stack): RecursiveVisitorStackItemInterface
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
     * @param mixed $subject
     *
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-param-out mixed $subject
     */
    private function selectSubject(array $stack, &$subject): bool
    {
        if (0 === ($count = count($stack))) {
            /** @psalm-var mixed */
            $subject = $this->subject;

            return true;
        }

        $top = $stack[$count - 1];

        $key = $top->key();
        $parentNode = $top->node();

        /** @psalm-var mixed */
        $parentSubject = $top->subjectResultCouple()->subject;

        if ($parentNode instanceof ValueSelectorWrapperInterface) {
            $parentValueSelector = $parentNode->getValueSelector();

            return $this->selectWithValueSelector($parentValueSelector, $parentSubject, $key, $subject);
        }

        if ($parentSubject instanceof \ArrayAccess) {
            if (!$parentSubject->offsetExists($key)) {
                return false;
            }

            /** @psalm-var mixed */
            $subject = $parentSubject->offsetGet($key);

            return true;
        }

        if (is_array($parentSubject) && array_key_exists($key, $parentSubject)) {
            /** @psalm-var mixed */
            $subject = $parentSubject[$key];

            return true;
        }

        return false;
    }

    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $retval
     *
     * @psalm-template SupportedSubject
     *
     * @psalm-param ValueSelectorInterface<SupportedSubject> $valueSelector
     * @psalm-param array-key                                $key
     *
     * @psalm-param-out mixed $retval
     */
    private function selectWithValueSelector(ValueSelectorInterface $valueSelector, $subject, $key, &$retval): bool
    {
        if (!$valueSelector->supports($subject)) {
            return false;
        }

        /** @psalm-suppress MissingThrowsDocblock */
        return $valueSelector->select($subject, $key, $retval);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-param ArrayLike $spec
     */
    private function enterIfSpecYieldsAnArray(iterable $spec, $subject): bool
    {
        if ($spec instanceof ValueSelectorWrapperInterface) {
            return $this->enterIfSelectionYieldsAnArray($spec, $subject);
        }

        if ($spec instanceof ResultFactoryWrapperInterface) {
            return $this->enterIfFactoryYieldsAnArray($spec, $subject, $subject);
        }

        if (!is_array($spec) || !is_array($subject)) {
            return false;
        }

        $this->subjectResultCouple = new SubjectResultCouple($subject, $subject);

        return true;
    }

    /**
     * @param mixed $spec
     * @param mixed $subject
     *
     * @psalm-template SupportedSubject
     *
     * @psalm-param ValueSelectorWrapperInterface<SupportedSubject> $spec
     */
    private function enterIfSelectionYieldsAnArray(ValueSelectorWrapperInterface $spec, $subject): bool
    {
        $valueSelector = $spec->getValueSelector();

        if (!$valueSelector->supports($subject)) {
            return false;
        }

        if (!$spec instanceof ResultFactoryWrapperInterface) {
            return false;
        }

        return $this->enterIfFactoryYieldsAnArray($spec, [], $subject);
    }

    /**
     * @param mixed $input
     * @param mixed $subject
     *
     * @psalm-template SupportedInput
     *
     * @psalm-param ResultFactoryWrapperInterface<SupportedInput> $spec
     *
     * @psalm-assert-if-true SupportedInput $input
     */
    private function enterIfFactoryYieldsAnArray(ResultFactoryWrapperInterface $spec, $input, $subject): bool
    {
        $factory = $spec->getResultFactory();

        if (!$factory->supports($input)) {
            return false;
        }

        if (null === $this->actual) {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace('$this->actual is null, did you call begin()?');
        }

        /** @psalm-suppress MissingThrowsDocblock */
        $result = $factory->getResult($this->actual, $input);

        if (!$result instanceof \ArrayAccess) {
            return false;
        }

        $this->subjectResultCouple = new SubjectResultCouple($subject, $result);

        return true;
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
