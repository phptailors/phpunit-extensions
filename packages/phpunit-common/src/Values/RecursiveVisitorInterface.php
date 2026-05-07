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
 */
interface RecursiveVisitorInterface
{
    /**
     * @param list<array-key>             $path
     * @param list<array|ValuesInterface> $stack
     */
    public function enter(array|ValuesInterface $node, array $path, array $stack): bool;

    /**
     * @param list<array-key>             $path
     * @param list<array|ValuesInterface> $stack
     */
    public function leave(array|ValuesInterface $node, array $path, array $stack, bool $iterating): void;

    /**
     * @param list<array-key>             $path
     * @param list<array|ValuesInterface> $stack
     */
    public function visit(mixed $node, array $path, array $stack, bool $iterating): void;

    /**
     * @param list<array-key>             $path
     * @param list<array|ValuesInterface> $stack
     */
    public function cycle(array|ValuesInterface $node, array $path, array $stack): bool;
}

// vim: syntax=php sw=4 ts=4 et:
