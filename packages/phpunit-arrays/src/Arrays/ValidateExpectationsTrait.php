<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
trait ValidateExpectationsTrait
{
    /**
     * @psalm-suppress UnusedParam
     *
     * @psalm-param ArrayLike $expected
     */
    protected static function validateExpectations(iterable $expected, int $argument, int $distance = 1): void {}
}

// vim: syntax=php sw=4 ts=4 et:
