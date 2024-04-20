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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

if (!function_exists('Tailors\\PHPUnit\\testInvalidArgumentExceptionFromBackTrace')) {
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
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(InvalidArgumentException::class)]
final class InvalidArgumentExceptionTest extends TestCase
{
    public static function provFromBackTrace(): array
    {
        return [
            'InvalidArgumentExceptionTest.php:'.__LINE__ => [
                1, 'a string', 'an integer',
            ],
        ];
    }

    #[DataProvider('provFromBackTrace')]
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

    #[DataProvider('provFromBackTrace')]
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
