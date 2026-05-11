<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\CircularDependencyException;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-implements RecursiveVisitorInterface<RecursiveUnwrapperStackItem>
 *
 * @psalm-type StackItem = RecursiveUnwrapperStackItem
 */
final class RecursiveUnwrapperVisitor implements RecursiveVisitorInterface
{
    public const UNIQUE_TAG = 'unwrapped-values:$1$zIlgusJc$ZZCyNRPOX1SbpKdzoD2hU/';

    /**
     * @var array
     */
    private array $result;

    public function __construct(private readonly bool $tagging = true)
    {
        $this->result = [];
    }

    /**
     * @psalm-mutation-free
     */
    public function result(): array
    {
        return $this->result;
    }

    /**
     * @param list<StackItem> $stack
     */
    public function enter(array|ValuesInterface $node, array $stack): bool
    {
        if ($node instanceof ValuesInterface) {
            $root = $node;
            foreach ($stack as $item) {
                $inode = $item->node();
                if ($inode instanceof ValuesInterface) {
                    $root = $inode;

                    break;
                }
            }
            $iterate = $root->actual() === $node->actual();
        } else {
            $iterate = true;
        }

        if ($iterate) {
            self::set($this->result, $stack, []);
        }

        return $iterate;
    }

    /**
     * @param list<StackItem> $stack
     */
    public function leave(array|ValuesInterface $node, array $stack, bool $iterating): void
    {
        if ($node instanceof ValuesInterface) {
            if ($this->tagging && $iterating) {
                // Distinguish unwrapped values from regular arrays
                // by adding UNIQUE TAG AT THE END of $array.
                $stack[] = new RecursiveUnwrapperStackItem($node, self::UNIQUE_TAG);
                self::set($this->result, $stack, true);
            }
        }
    }

    /**
     * @param list<StackItem> $stack
     */
    public function visit(mixed $node, array $stack, bool $iterating): void
    {
        self::set($this->result, $stack, $node);
    }

    /**
     * @param list<StackItem> $stack
     *
     * @return never
     *
     * @throws CircularDependencyException
     */
    public function cycle(array|ValuesInterface $node, array $stack): bool
    {
        self::throwCircular($stack);
    }

    /**
     * @param array-key       $key
     * @param list<StackItem> $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem(array|ValuesInterface $node, $key, array $stack): RecursiveVisitorStackItemInterface
    {
        return new RecursiveUnwrapperStackItem($node, $key);
    }

    /**
     * @param StackItem       $item
     * @param list<StackItem> $stack
     */
    public function freeStackItem(RecursiveVisitorStackItemInterface $item, array $stack): void {}

    /**
     * @param array           $array
     * @param list<StackItem> $stack
     *
     * @psalm-suppress UnusedParam
     * @psalm-suppress UnusedVariable
     */
    private static function set(array &$array, array $stack, mixed $value): void
    {
        if (0 === count($stack)) {
            if (is_array($value)) {
                $array = $value;
            }

            return;
        }

        $current = &$array;

        $top = array_pop($stack)->key();
        foreach ($stack as $item) {
            $key = $item->key();
            if (!array_key_exists($key, $current)) {
                $current[$key] = [];
            }

            if (!is_array($current[$key])) {
                return;
            }

            $current = &$current[$key];
        }

        /** @psalm-var mixed */
        $current[$top] = $value;
    }

    /**
     * @param list<StackItem> $stack
     *
     * @return never
     *
     * @throws CircularDependencyException
     */
    private static function throwCircular(array $stack): never
    {
        $pathString = self::pathString($stack);

        throw new CircularDependencyException("Circular dependency found in nested values at \$values{$pathString}.");
    }

    /**
     * @param list<StackItem> $stack
     *
     * @psalm-mutation-free
     */
    private static function pathString(array $stack): string
    {
        return implode('', array_map(fn ($item) => '['.var_export($item->key(), true).']', $stack));
    }
}

// vim: syntax=php sw=4 ts=4 et:
