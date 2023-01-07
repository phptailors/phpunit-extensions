<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Comparator;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
interface ComparatorInterface
{
    /**
     * Compares two values.
     *
     * @param mixed $left
     * @param mixed $right
     */
    public function compare($left, $right): bool;

    /**
     * Returns an adjective that identifies this comparison operator.
     *
     * Shall return strings such as "equal to" (equality operator ``==``),
     * "identical to" (identity operator ``===``), etc..
     */
    public function adjective(): string;
}

// vim: syntax=php sw=4 ts=4 et:
