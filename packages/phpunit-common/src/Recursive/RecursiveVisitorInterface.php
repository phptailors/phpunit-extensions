<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Recursive;

use Tailors\PHPUnit\Values\ValuesInterface;

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
     * @psalm-param list<StackItem> $stack
     */
    public function enter(array|ValuesInterface $node, array $stack): bool;

    /**
     * @psalm-param list<StackItem> $stack
     */
    public function leave(array|ValuesInterface $node, array $stack, bool $iterating): void;

    /**
     * @psalm-param list<StackItem> $stack
     */
    public function visit(mixed $node, array $stack, bool $iterating): void;

    /**
     * @psalm-param list<StackItem> $stack
     */
    public function cycle(array|ValuesInterface $node, array $stack): bool;

    /**
     * @psalm-param array-key       $key
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem(array|ValuesInterface $node, mixed $key, array $stack): RecursiveVisitorStackItemInterface;

    /**
     * @psalm-param StackItem       $item
     * @psalm-param list<StackItem> $stack
     */
    public function freeStackItem(RecursiveVisitorStackItemInterface $item, array $stack): void;
}

// vim: syntax=php sw=4 ts=4 et:
