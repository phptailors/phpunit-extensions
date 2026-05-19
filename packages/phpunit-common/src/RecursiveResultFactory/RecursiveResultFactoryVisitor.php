<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Recursive;

use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\InternalErrorException;
use Tailors\PHPUnit\Result\ResultFactoryInterface;
use Tailors\PHPUnit\Result\ResultFactoryWrapperInterface;
use Tailors\PHPUnit\Selector\ValueSelectorInterface;
use Tailors\PHPUnit\Selector\ValueSelectorWrapperInterface;
use Tailors\PHPUnit\Spec\ArraySpecInterface;

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
     * @var ArraySpecInterface
     *
     * @psalm-readonly
     */
    private $node;

    /**
     * @var bool
     *
     * @psalm-readonly
     */
    private $actual;

    /**
     * @var mixed
     *
     * @psalm-readonly
     */
    private $subject;

    /**
     * @var mixed
     */
    private $result;

    /**
     * @var ?RecursiveResultFactoryState
     */
    private $state;

    /**
     * @param mixed $subject
     */
    public function __construct(bool $actual, ArraySpecInterface $node, $subject)
    {
        $this->actual = $actual;
        $this->node = $node;
        $this->subject = $subject;
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
        if (!$this->selectIfIterable($node, $stack, $subject, $result)) {
            return false;
        }

        $this->state = new RecursiveResultFactoryState($subject, $result);

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

        if (null === $this->state) {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace('$this->state is null while $iterating');
        }

        $count = count($stack);
        if (0 === $count) {
            $this->result = $this->state->result;

            return;
        }

        $last = $count - 1;
        $top = $stack[$last];
        $top->set($this->state->result);
    }

    /**
     * @param mixed $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function visit($node, array $stack, bool $iterating): void
    {
        if (0 === count($stack)) {
            $this->result = $this->subject;

            return;
        }

        if (!$this->selectNested($stack, $result)) {
            return;
        }

        $last = count($stack) - 1;
        $top = $stack[$last];
        $top->set($result);
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
        if (null === $this->state) {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace('$this->state is null');
        }

        return new RecursiveResultFactoryStackItem($node, $key, $this->state);
    }

    /**
     * @psalm-param StackItem       $item
     * @psalm-param list<StackItem> $stack
     */
    public function freeStackItem(RecursiveVisitorStackItemInterface $item, array $stack): void
    {
        $this->state = $item->state();
    }

    /**
     * @param array|ArraySpecInterface $node
     * @param mixed                 $subject
     * @param mixed                 $result
     *
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-param-out mixed $subject
     * @psalm-param-out mixed $result
     *
     * @psalm-assert-if-true array|ArraySpecInterface $result
     */
    private function selectIfIterable($node, array $stack, &$subject, &$result): bool
    {
        $parentState = $this->getParentState($stack);



        if ($parentNode instanceof ValueSelectorWrapperInterface) {
            $valueSelector = $parentNode->getValueSelector();
        }

        if (0 === count($stack)) {
            return $this->selectValueIfIterable($node, $this->subject, $subject, $result);
        }

        return $this->selectNestedIfIterable($node, $stack, $subject, $result);
    }

    /**
     * @param array|ArraySpecInterface $node
     * @param mixed                 $subject
     * @param mixed                 $result
     *
     * @psalm-param non-empty-list<StackItem> $stack
     *
     * @psalm-param-out mixed $subject
     * @psalm-param-out mixed $result
     *
     * @psalm-assert-if-true array|ArraySpecInterface $result
     */
    private function selectNestedIfIterable($node, array $stack, &$subject, &$result): bool
    {
        $last = count($stack) - 1;

        $top = $stack[$last];
        $key = $top->key();
        $parentNode = $top->node();

        /** @psalm-var mixed */
        $parentSubject = $top->state()->subject;

        if ($parentNode instanceof ArraySpecInterface) {
            /** @psalm-suppress MissingThrowsDocblock */
            if (!$this->getResultFactory($parentNode)->select($parentSubject, $key, $value)) {
                return false;
            }
        } elseif (is_array($parentSubject)) {
            if (!array_key_exists($key, $parentSubject)) {
                return false;
            }

            /** @psalm-var mixed */
            $value = $parentSubject[$key];
        } else {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace(
                "\$stack[{$last}]->node() is an array, but \$stack[{$last}]->state()->subject is not"
            );
        }

        return $this->selectValueIfIterable($node, $value, $subject, $result);
    }

    /**
     * @param array|ArraySpecInterface $node
     * @param mixed                 $value
     * @param mixed                 $subject
     * @param mixed                 $result
     *
     * @psalm-template T
     *
     * @psalm-param T $value
     *
     * @psalm-param-out mixed $subject
     * @psalm-param-out mixed $result
     *
     * @psalm-assert-if-true T $subject
     * @psalm-assert-if-true array|ArraySpecInterface $result
     */
    private function selectValueIfIterable($node, $value, &$subject, &$result): bool
    {
//        if ($node instanceof ArraySpecInterface && !$node->actual()) {
//            if (!$this->getResultFactory($node)->supports($value)) {
//                return false;
//            }
//
//            $subject = $value;
//            $result = $node->createActualValues();
//
//            return true;
//        }
//
//        if (!is_array($node) || !is_array($value)) {
//            return false;
//        }
//
//        // Just copy the array.
//        $subject = $value;
//        $result = $value;
//
//        return true;
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
        $parentSubject = $top->state()->subject;

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
        $pathString = self::pathString($stack);

        throw new CircularDependencyException("Circular dependency found in nested values at \$values{$pathString}.");
    }

    /**
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-mutation-free
     */
    private static function pathString(array $stack): string
    {
        return implode('', array_map(function ($item) {
            return '['.var_export($item->key(), true).']';
        }, $stack));
    }

    /**
     * @param list<RecursiveResultFactoryStackItem> $stack
     */
    private function getParentState(array $stack): RecursiveResultFactoryState
    {
        if (0 === ($count = count($stack))) {
            return new RecursiveResultFactoryState($this->node, $this->subject, $this->result);
        }

        return $stack[$count - 1]->state();
    }

}

// vim: syntax=php sw=4 ts=4 et:
