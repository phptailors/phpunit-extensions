<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use Tailors\PHPUnit\ArraySpec\ArraySpecInterface;
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
 * @psalm-type ArrayNode array|ArraySpecInterface
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
     * @param array|ArraySpecInterface $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function enter($node, array $stack): bool
    {
        if (!$this->selectIfSupported($node, $stack)) {
            return false;
        }

        $this->subjectResultCouple = new SubjectResultCouple($subject, $result);

        return true;
    }

    /**
     * @param array|ArraySpecInterface $node
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
            throw InternalErrorException::fromBackTrace('$this->subjectResultCouple is null while $iterating');
        }

        $count = count($stack);
        if (0 === $count) {
            $this->result = $this->subjectResultCouple->result;

            return;
        }

        $stack[$count - 1]->set($this->subjectResultCouple->result);
    }

    /**
     * @param mixed $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function visit($node, array $stack, bool $iterating): void
    {
        if (0 === ($count = count($stack))) {
            // FIXME: transform?
            $this->result = $this->subject;

            return;
        }

        if (!$this->selectNested($stack, $result)) {
            return;
        }

        // FIXME: transform
        $stack[$count - 1]->set($result);
    }

    /**
     * @param array|ArraySpecInterface $node
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
     * @param array|ArraySpecInterface $node
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
     * @param array|ArraySpecInterface $node
     *
     * @psalm-param list<StackItem> $stack
     */
    private function selectIfSupported($node, array $stack): ?SubjectResultCouple
    {
        if (0 === count($stack)) {
            $nodeSubjectCouple = new NodeSubjectCouple($node, $this->subject);
            return $this->makeSubjectResultCouple($nodeSubjectCouple);
        }

        return $this->selectNestedIfIterable($node, $stack);
    }

    /**
     * @param array|ArraySpecInterface $node
     *
     * @psalm-param non-empty-list<StackItem> $stack
     */
    private function selectNestedIfIterable($node, array $stack): ?SubjectResultCouple
    {
        $last = count($stack) - 1;

        $top = $stack[$last];
        $key = $top->key();
        $parentNode = $top->node();

        /** @psalm-var mixed */
        $parentSubject = $top->subjectResultCouple()->subject;

        if ($parentNode instanceof ArraySpecInterface) {
            /** @psalm-suppress MissingThrowsDocblock */
            if (!$this->getResultFactory($parentNode)->select($parentSubject, $key, $value)) {
                return false;
            }
        } elseif (is_array($parentSubject)) {
            if (!array_key_exists($key, $parentSubject)) {
                return null;
            }

            /** @psalm-var mixed */
            $value = $parentSubject[$key];
        } else {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace(
                "\$stack[{$last}]->node() is an array, but \$stack[{$last}]->subjectResultCouple()->subject is not"
            );
        }

        return $this->makeSubjectResultCouple($node);
    }

    private function makeSubjectResultCouple(NodeSubjectCouple $nodeSubjectCouple): ?SubjectResultCouple
    {
        $node = $nodeSubjectCouple->node;
        $subject = $nodeSubjectCouple->subject;

        if ($node instanceof ValueSelectorWrapperInterface) {
            $valueSelector = $node->getValueSelector();

            if (!$valueSelector->supports($subject)) {
                return null;
            }

            return self::makeSubjectResultCoupleFromNode($node, []);
        }

        if ($node instanceof ResultFactoryWrapperInterface) {
            $factory = $node->getResultFactory();

            return self::makeSubjectResultCoupleFromFactory($factory, $subject);
        }

        if (!is_array($node) || !is_array($subject)) {
            return null;
        }

        // Just copy the array
        return new SubjectResultCouple($subject, $subject);
    }

    /**
     * @param mixed $node
     * @param mixed $subject
     */
    private static function makeSubjectResultCoupleFromNode($node, $subject): ?SubjectResultCouple
    {
        if (!$node instanceof ResultFactoryWrapperInterface) {
            return null;
        }

        $factory = $node->getResultFactory();

        return $this->makeSubjectResultCoupleFromFactory($factory, $subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-template SupportedInput
     * @psalm-param ResultFactoryInterface<SupportedInput> $factory
     */
    private static function makeSubjectResultCoupleFromFactory(ResultFactoryInterface $factory, $subject): ?SubjectResultCouple
    {
        if (!$factory->supports($subject)) {
            return null;
        }

        $result = $this->actual ? $factory->getActualResult($subject) : $factory->getExpectedResult($subject);

        return new SubjectResultCouple($subject, $result);
    }

    /**
     * @param mixed $result
     *
     * @psalm-param non-empty-list<StackItem> $stack
     *
     * @psalm-param-out mixed $result
     */
    private function selectNested(array $stack, &$result): bool
    {
        $last = count($stack) - 1;
        $top = $stack[$last];
        $key = $top->key();
        $parentNode = $top->node();

        /** @psalm-var mixed */
        $parentSubject = $top->subjectResultCouple()->subject;

        if (!$parentNode instanceof ArraySpecInterface) {
            // array, leave its terminal item as is.
            return false;
        }

        /** @psalm-suppress MissingThrowsDocblock */
        if (!$this->getResultFactory($parentNode)->select($parentSubject, $key, $result)) {
            return false;
        }

        return true;
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

        throw new CircularDependencyException("Circular dependency found in nested values at \$values{$pathString}.");
    }
}

// vim: syntax=php sw=4 ts=4 et:
