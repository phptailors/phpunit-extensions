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
use Tailors\PHPUnit\Selector\ValueSelectorInterface;
use Tailors\PHPUnit\Selector\ValueSelectorWrapperInterface;
use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type StackItem RecursiveSelectorStackItem
 *
 * @template-implements RecursiveVisitorInterface<RecursiveSelectorStackItem>
 */
final class RecursiveSelectorVisitor implements RecursiveVisitorInterface
{
    private mixed $result = null;

    private ?RecursiveSelectorState $state = null;

    public function __construct(private readonly ValueSelectorInterface $valueSelector, private readonly mixed $subject) {}

    /**
     * @psalm-mutation-free
     */
    public function result(): mixed
    {
        return $this->result;
    }

    /**
     * @psalm-param list<StackItem> $stack
     */
    public function enter(array|ValuesInterface $node, array $stack): bool
    {
        if (!$this->selectIfIterable($node, $stack, $subject, $result)) {
            return false;
        }

        $this->state = new RecursiveSelectorState($subject, $result);

        return true;
    }

    /**
     * @psalm-param list<StackItem> $stack
     */
    public function leave(array|ValuesInterface $node, array $stack, bool $iterating): void
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
     * @psalm-param list<StackItem> $stack
     */
    public function visit(mixed $node, array $stack, bool $iterating): void
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
     * @throws CircularDependencyException
     *
     * @psalm-param list<StackItem> $stack
     */
    public function cycle(array|ValuesInterface $node, array $stack): bool
    {
        self::throwCircular($stack);
    }

    /**
     * @psalm-param array-key       $key
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem(array|ValuesInterface $node, mixed $key, array $stack): RecursiveVisitorStackItemInterface
    {
        if (null === $this->state) {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace('$this->state is null');
        }

        return new RecursiveSelectorStackItem($node, $key, $this->state);
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
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-param-out mixed $subject
     * @psalm-param-out mixed $result
     *
     * @psalm-assert-if-true array|ValuesInterface $result
     */
    private function selectIfIterable(array|ValuesInterface $node, array $stack, mixed &$subject, mixed &$result): bool
    {
        if (0 === count($stack)) {
            return $this->selectValueIfIterable($node, $this->subject, $subject, $result);
        }

        return $this->selectNestedIfIterable($node, $stack, $subject, $result);
    }

    /**
     * @psalm-param non-empty-list<StackItem> $stack
     *
     * @psalm-param-out mixed $subject
     * @psalm-param-out mixed $result
     *
     * @psalm-assert-if-true array|ValuesInterface $result
     */
    private function selectNestedIfIterable(array|ValuesInterface $node, array $stack, mixed &$subject, mixed &$result): bool
    {
        $last = count($stack) - 1;

        $top = $stack[$last];
        $key = $top->key();
        $parentNode = $top->node();

        /** @psalm-var mixed */
        $parentSubject = $top->state()->subject;

        if ($parentNode instanceof ValuesInterface) {
            /** @psalm-suppress MissingThrowsDocblock */
            if (!$this->getValueSelector($parentNode)->select($parentSubject, $key, $value)) {
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
     * @psalm-template T
     *
     * @psalm-param T $value
     *
     * @psalm-param-out mixed $subject
     * @psalm-param-out mixed $result
     *
     * @psalm-assert-if-true T $subject
     * @psalm-assert-if-true array|ValuesInterface $result
     */
    private function selectValueIfIterable(array|ValuesInterface $node, mixed $value, mixed &$subject, mixed &$result): bool
    {
        if ($node instanceof ValuesInterface && !$node->actual()) {
            if (!$this->getValueSelector($node)->supports($value)) {
                return false;
            }

            $subject = $value;
            $result = $node->createActualValues();

            return true;
        }

        if (!is_array($node) || !is_array($value)) {
            return false;
        }

        // Just copy the array.
        $subject = $value;
        $result = $value;

        return true;
    }

    /**
     * @psalm-param non-empty-list<StackItem> $stack
     *
     * @psalm-param-out mixed $result
     */
    private function selectNested(array $stack, mixed &$result): bool
    {
        $last = count($stack) - 1;
        $top = $stack[$last];
        $key = $top->key();
        $parentNode = $top->node();

        /** @psalm-var mixed */
        $parentSubject = $top->state()->subject;

        if (!$parentNode instanceof ValuesInterface) {
            // array, leave its terminal item as is.
            return false;
        }

        /** @psalm-suppress MissingThrowsDocblock */
        if (!$this->getValueSelector($parentNode)->select($parentSubject, $key, $result)) {
            return false;
        }

        return true;
    }

    /**
     * @throws CircularDependencyException
     *
     * @psalm-param list<StackItem> $stack
     */
    private static function throwCircular(array $stack): never
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
        return implode('', array_map(fn ($item) => '['.var_export($item->key(), true).']', $stack));
    }

    private function getValueSelector(object $node): ValueSelectorInterface
    {
        if ($node instanceof ValueSelectorWrapperInterface) {
            return $node->getValueSelector();
        }

        return $this->valueSelector;
    }
}

// vim: syntax=php sw=4 ts=4 et:
