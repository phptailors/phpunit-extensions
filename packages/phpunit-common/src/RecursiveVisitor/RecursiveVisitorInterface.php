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
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template StackItem of RecursiveVisitorStackItemInterface
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
interface RecursiveVisitorInterface
{
    /**
     * @psalm-param ArrayLike $node
     * @psalm-param list<StackItem> $stack
     */
    public function enter(iterable $node, array $stack): bool;

    /**
     * @psalm-param ArrayLike $node
     * @psalm-param list<StackItem> $stack
     */
    public function leave(iterable $node, array $stack, bool $iterating): void;

    /**
     * @param mixed $node
     * @psalm-param list<StackItem> $stack
     */
    public function visit($node, array $stack, bool $iterating): void;

    /**
     * @psalm-param ArrayLike $node
     * @psalm-param list<StackItem> $stack
     */
    public function cycle(iterable $node, array $stack): bool;

    /**
     * @param mixed                 $key
     *
     * @psalm-param ArrayLike $node
     * @psalm-param array-key       $key
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem(iterable $node, $key, array $stack): RecursiveVisitorStackItemInterface;

    /**
     * @psalm-param StackItem       $item
     * @psalm-param list<StackItem> $stack
     */
    public function freeStackItem(RecursiveVisitorStackItemInterface $item, array $stack): void;
}

// vim: syntax=php sw=4 ts=4 et:
