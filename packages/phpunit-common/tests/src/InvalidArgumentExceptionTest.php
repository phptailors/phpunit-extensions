<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

if (!function_exists('Tailors\\PHPUnit\\testInvalidArgumentExceptionFromBackTrace')) {
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    function testInvalidArgumentExceptionFromBackTrace(
        int $argument,
        string $expected,
        string $provided
    ): InvalidArgumentException {
        $message = sprintf(
            'Argument %d passed to %s() must be %s, %s given.',
            $argument,
            __FUNCTION__,
            $expected,
            $provided
        );

        $exception = InvalidArgumentException::fromBackTrace($argument, $expected, $provided);
        Assert::assertSame($message, $exception->getMessage());

        return InvalidArgumentException::fromBackTrace($argument, $expected, $provided, 2);
    }
}

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\InvalidArgumentException
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class InvalidArgumentExceptionTest extends TestCase
{
    /**
     * @psalm-return iterable<string, list{int, string, string}>
     */
    public static function provFromBackTrace(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            1, 'a string', 'an integer',
        ];
    }

    /**
     * @dataProvider provFromBackTrace
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFromBackTrace(int $argument, string $expected, string $provided): void
    {
        $message = sprintf(
            'Argument %d passed to %s() must be %s, %s given.',
            $argument,
            __METHOD__,
            $expected,
            $provided
        );

        $exception = InvalidArgumentException::fromBackTrace($argument, $expected, $provided);
        self::assertSame($message, $exception->getMessage());
    }

    /**
     * @dataProvider provFromBackTrace
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFromBackTraceFromFunction(int $argument, string $expected, string $provided): void
    {
        $message = sprintf(
            'Argument %d passed to %s() must be %s, %s given.',
            $argument,
            __METHOD__,
            $expected,
            $provided
        );

        $exception = testInvalidArgumentExceptionFromBackTrace($argument, $expected, $provided);
        self::assertSame($message, $exception->getMessage());
    }
}

// vim: syntax=php sw=4 ts=4 et:
