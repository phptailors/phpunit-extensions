<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @internal This trait is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
trait ValidateExpectationsTrait
{
    /**
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    protected static function validateExpectations(array $expected, int $argument, int $distance = 1): void
    {
        self::assertStringKeysOnly($expected, $argument, 1 + $distance);
    }

    /**
     * @psalm-assert array<string, mixed> $array
     *
     * @throws InvalidArgumentException
     */
    private static function assertStringKeysOnly(array $array, int $argument, int $distance = 1): void
    {
        $valid = array_filter($array, 'is_string', ARRAY_FILTER_USE_KEY);
        if (($count = count($array) - count($valid)) > 0) {
            throw InvalidArgumentException::fromBackTrace(
                $argument,
                'an associative array with string keys',
                sprintf('an array with %d non-string %s', $count, $count > 1 ? 'keys' : 'key'),
                1 + $distance
            );
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
