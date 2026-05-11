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
use Tailors\PHPUnit\InvalidArgumentException;

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

    /**
     * @var array
     */
    private array $current;

    public function __construct(private readonly bool $tagging = true)
    {
        $this->result = [];
        $this->current = [];
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
            $this->current = [];
        }

        return $iterate;
    }

    /**
     * @param list<StackItem> $stack
     */
    public function leave(array|ValuesInterface $node, array $stack, bool $iterating): void
    {
        if (!$iterating) {
            return;
        }

        if ($node instanceof ValuesInterface && $this->tagging) {
            // Distinguish unwrapped values from regular arrays
            // by adding UNIQUE TAG AT THE END of $array.
            $this->current[self::UNIQUE_TAG] = true;
        }

        $this->set($stack, $this->current);
    }

    /**
     * @param list<StackItem> $stack
     */
    public function visit(mixed $node, array $stack, bool $iterating): void
    {
        $this->set($stack, $node);
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
        return new RecursiveUnwrapperStackItem($node, $key, $this->current);
    }

    /**
     * @param StackItem       $item
     * @param list<StackItem> $stack
     */
    public function freeStackItem(RecursiveVisitorStackItemInterface $item, array $stack): void
    {
        $this->current = $item->result();
    }

    /**
     * @param list<StackItem> $stack
     */
    private function set(array $stack, mixed $value): void
    {
        $count = count($stack);

        if (0 === $count) {
            if (!is_array($value)) {
                $actual = get_debug_type($value);

                /** @psalm-suppress MissingThrowsDocblock */
                throw InvalidArgumentException::fromBackTrace(2, 'an array', $actual);
            }
            $this->result = $value;

            return;
        }

        $last = $count - 1;
        $top = $stack[$last];
        $top->set($value);
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
