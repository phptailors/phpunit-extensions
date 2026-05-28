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
 */
interface RecursiveVisitorStackItemInterface
{
    /**
     * @psalm-return iterable<array-key, mixed>
     *
     * @psalm-mutation-free
     */
    public function node(): iterable;

    /**
     * @return mixed
     *
     * @psalm-return array-key
     *
     * @psalm-mutation-free
     */
    public function key();
}

// vim: syntax=php sw=4 ts=4 et:
