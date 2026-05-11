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
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template StackItem of RecursiveVisitorStackItemInterface
 */
interface RecursiveVisitorInterface
{
    /**
     * @param list<StackItem> $stack
     */
    public function enter(array|ValuesInterface $node, array $stack): bool;

    /**
     * @param list<StackItem> $stack
     */
    public function leave(array|ValuesInterface $node, array $stack, bool $iterating): void;

    /**
     * @param list<StackItem> $stack
     */
    public function visit(mixed $node, array $stack, bool $iterating): void;

    /**
     * @param list<StackItem> $stack
     */
    public function cycle(array|ValuesInterface $node, array $stack): bool;

    /**
     * @param array-key       $key
     * @param list<StackItem> $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem(array|ValuesInterface $node, $key, array $stack): RecursiveVisitorStackItemInterface;

    /**
     * @param StackItem       $item
     * @param list<StackItem> $stack
     */
    public function freeStackItem(RecursiveVisitorStackItemInterface $item, array $stack): void;
}

// vim: syntax=php sw=4 ts=4 et:
