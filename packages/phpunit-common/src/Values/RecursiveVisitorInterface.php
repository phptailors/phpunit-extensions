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
     * @param array|ValuesInterface $node
     * @param list<array-key>       $path
     */
    public function enter($node, array $path): bool;

    /**
     * @param array|ValuesInterface $node
     * @param list<array-key>       $path
     */
    public function leave($node, array $path, bool $iterating): void;

    /**
     * @param mixed           $node
     * @param list<array-key> $path
     */
    public function visit($node, array $path, bool $iterating): void;

    /**
     * @param array|ValuesInterface $node
     * @param list<array-key>       $path
     */
    public function cycle($node, array $path): bool;
}

// vim: syntax=php sw=4 ts=4 et:
