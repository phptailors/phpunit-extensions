<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

/**
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class IdentityComparator implements ComparatorInterface
{
    /**
     * @param mixed $left
     * @param mixed $right
     */
    public function compare($left, $right): bool
    {
        return $left === $right;
    }

    /**
     * Returns an adjective that identifies this comparison operator.
     *
     * @return string "identical to"
     */
    public function adjective(): string
    {
        return 'identical to';
    }
}

// vim: syntax=php sw=4 ts=4 et:
