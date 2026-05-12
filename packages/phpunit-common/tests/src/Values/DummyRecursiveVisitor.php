<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-implements RecursiveVisitorInterface<DummyRecursiveVisitorStackItem>
 *
 * @psalm-type StackItem = DummyRecursiveVisitorStackItem
 */
final class DummyRecursiveVisitor implements RecursiveVisitorInterface
{
    /**
     * @var list<array{func: string, node:mixed, path:list<array-key>}>
     */
    private array $trace;

    /**
     * @param bool|\Closure(array|ValuesInterface,list<StackItem>):bool $enter
     * @param bool|\Closure(array|ValuesInterface,list<StackItem>):bool $cycle
     */
    public function __construct(private readonly bool|\Closure $enter = true, private readonly bool|\Closure $cycle = false)
    {
        $this->trace = [];
    }

    /**
     * @psalm-param list<StackItem>       $stack
     */
    public function enter(array|ValuesInterface $node, array $stack): bool
    {
        $this->trace[] = ['func' => 'enter', 'node' => &$node, 'path' => self::path($stack)];

        if (is_bool($this->enter)) {
            return $this->enter;
        }

        return call_user_func_array($this->enter, [&$node, $stack]);
    }

    /**
     * @psalm-param list<StackItem>       $stack
     */
    public function leave(array|ValuesInterface $node, array $stack, bool $iterating): void
    {
        $this->trace[] = ['func' => 'leave', 'node' => &$node, 'path' => self::path($stack)];
    }

    /**
     * @psalm-param list<StackItem>       $stack
     */
    public function visit(mixed $node, array $stack, bool $iterating): void
    {
        $this->trace[] = ['func' => 'visit', 'node' => &$node, 'path' => self::path($stack)];
    }

    /**
     * @psalm-param list<StackItem>       $stack
     */
    public function cycle(array|ValuesInterface $node, array $stack): bool
    {
        $this->trace[] = ['func' => 'cycle', 'node' => &$node, 'path' => self::path($stack)];

        if (is_bool($this->cycle)) {
            return $this->cycle;
        }

        return call_user_func_array($this->cycle, [&$node, $stack]);
    }

    /**
     * @param mixed $key
     *
     * @psalm-param array-key             $key
     * @psalm-param list<StackItem>       $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem(array|ValuesInterface $node, $key, array $stack): RecursiveVisitorStackItemInterface
    {
        return new DummyRecursiveVisitorStackItem($node, $key);
    }

    /**
     * @param RecursiveVisitorStackItemInterface $item
     * @param array                              $stack
     *
     * @psalm-param StackItem       $item
     * @psalm-param list<StackItem> $stack
     */
    public function freeStackItem(RecursiveVisitorStackItemInterface $item, array $stack): void {}

    /**
     * @return list<array{func: string, node:mixed, path:list<array-key>}>
     *
     * @psalm-mutation-free
     */
    public function trace(): array
    {
        return $this->trace;
    }

    /**
     * @param array $stack
     *
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-pure
     */
    private static function path(array $stack): array
    {
        return array_map(fn ($item) => $item->key(), $stack);
    }
}
// vim: syntax=php sw=4 ts=4 et:
