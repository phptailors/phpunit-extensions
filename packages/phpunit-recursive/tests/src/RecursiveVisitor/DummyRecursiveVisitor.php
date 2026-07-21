<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveVisitor;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-implements RecursiveVisitorInterface<DummyRecursiveVisitorStackItem>
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 * @psalm-type TraceItem = array{func: string, node: mixed, key?: array-key, path: list<array-key>}
 * @psalm-type Trace     = list<TraceItem>
 * @psalm-type StackItem = DummyRecursiveVisitorStackItem
 */
final class DummyRecursiveVisitor implements RecursiveVisitorInterface
{
    /**
     * @var array
     *
     * @psalm-var Trace
     */
    private $trace;

    /**
     * @var bool|\Closure
     *
     * @psalm-var bool|\Closure(ArrayLike, list<StackItem>):bool
     */
    private $enter;

    /**
     * @var bool|\Closure
     *
     * @psalm-var bool|\Closure(ArrayLike, list<StackItem>):bool
     */
    private $cycle;

    /**
     * @param bool|\Closure $enter
     * @param bool|\Closure $cycle
     *
     * @psalm-param bool|\Closure(ArrayLike, list<StackItem>):bool $enter
     * @psalm-param bool|\Closure(ArrayLike, list<StackItem>):bool $cycle
     */
    public function __construct($enter = true, $cycle = false)
    {
        $this->trace = [];
        $this->enter = $enter;
        $this->cycle = $cycle;
    }

    /**
     * @psalm-param ArrayLike       $node
     * @psalm-param list<StackItem> $stack
     */
    public function enter(iterable $node, array $stack): bool
    {
        $this->trace[] = ['func' => 'enter', 'node' => $node, 'path' => self::path($stack)];

        if (is_bool($this->enter)) {
            return $this->enter;
        }

        return call_user_func_array($this->enter, [$node, $stack]);
    }

    /**
     * @psalm-param ArrayLike       $node
     * @psalm-param list<StackItem> $stack
     */
    public function leave(iterable $node, array $stack, bool $iterating): void
    {
        $this->trace[] = ['func' => 'leave', 'node' => $node, 'path' => self::path($stack)];
    }

    /**
     * @param mixed $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function visit($node, array $stack, bool $iterating): void
    {
        $this->trace[] = ['func' => 'visit', 'node' => $node, 'path' => self::path($stack)];
    }

    /**
     * @psalm-param ArrayLike       $node
     * @psalm-param list<StackItem> $stack
     */
    public function cycle(iterable $node, array $stack): bool
    {
        $this->trace[] = ['func' => 'cycle', 'node' => $node, 'path' => self::path($stack)];

        if (is_bool($this->cycle)) {
            return $this->cycle;
        }

        return call_user_func_array($this->cycle, [$node, $stack]);
    }

    /**
     * @param mixed     $key
     * @param ArrayLike $node
     *
     * @psalm-param array-key       $key
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem(iterable $node, $key, array $stack): RecursiveVisitorStackItemInterface
    {
        $this->trace[] = ['func' => 'makeStackItem', 'node' => $node, 'key' => $key, 'path' => self::path($stack)];

        return new DummyRecursiveVisitorStackItem($node, $key);
    }

    /**
     * @psalm-param StackItem       $item
     * @psalm-param list<StackItem> $stack
     */
    public function freeStackItem(RecursiveVisitorStackItemInterface $item, array $stack): void
    {
        $this->trace[] = ['func' => 'freeStackItem', 'node' => $item->node(), 'key' => $item->key(), 'path' => self::path($stack)];
    }

    /**
     * @psalm-return Trace
     *
     * @psalm-mutation-free
     */
    public function trace(): array
    {
        return $this->trace;
    }

    /**
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-return list<array-key>
     *
     * @psalm-pure
     */
    private static function path(array $stack): array
    {
        return array_map(function ($item) { return $item->key(); }, $stack);
    }
}
// vim: syntax=php sw=4 ts=4 et:
