<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
trait ValidateExpectationsTrait
{
    /**
     * @psalm-suppress UnusedParam
     */
    protected static function validateExpectations(array $expected, int $argument, int $distance = 1): void
    {
    }
}

// vim: syntax=php sw=4 ts=4 et:
