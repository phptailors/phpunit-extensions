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
     * @param array|ValuesInterface $node
     * @param list<StackItem>       $stack
     */
    public function enter($node, array $stack): bool;

    /**
     * @param array|ValuesInterface $node
     * @param list<StackItem>       $stack
     */
    public function leave($node, array $stack, bool $iterating): void;

    /**
     * @param mixed           $node
     * @param list<StackItem> $stack
     */
    public function visit($node, array $stack, bool $iterating): void;

    /**
     * @param array|ValuesInterface $node
     * @param list<StackItem>       $stack
     */
    public function cycle($node, array $stack): bool;

    /**
     * @param array|ValuesInterface $node
     * @param array-key             $key
     * @param list<StackItem>       $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem($node, $key, array $stack): RecursiveVisitorStackItemInterface;
}

// vim: syntax=php sw=4 ts=4 et:
