<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(InvalidReturnValueException::class)]
#[Small]
final class InvalidReturnValueExceptionTest extends TestCase
{
    public static function provFromExpectedAndActual(): iterable
    {
        yield 'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
            'sprintf', 'a string', 'integer',
        ];

        yield 'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
            'inexistentFunction', 'a string', 'integer',
        ];

        yield 'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
            fn (string $s): string => 2, 'a string', 'integer',
        ];

        yield 'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
            123, 'a string', 'integer',
        ];

        yield 'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
            null, 'a string', 'integer',
        ];
    }

    #[DataProvider('provFromExpectedAndActual')]
    public function testFromExpectedAndActual(mixed $function, string $expected, string $actual): void
    {
        $name = self::getFunctionName($function);
        $message = sprintf('Return value of %s() must be %s, %s returned', $name, $expected, $actual);

        $exception = InvalidReturnValueException::fromExpectedAndActual($name, $expected, $actual);
        self::assertSame($message, $exception->getMessage());
    }

    public static function provFromExpectedTypeAndActualValue(): iterable
    {
        yield 'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
            'sprintf', 'string', 123,
        ];

        yield 'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
            'inexistentFunction', 'string', 123,
        ];

        yield 'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
            self::provFromExpectedTypeAndActualValue(...), 'string', null,
        ];

        yield 'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
            [self::class, 'inexistentMethod'], 'string', null,
        ];

        yield 'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
            '', 'string', new \stdClass(),
        ];

        yield 'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
            fn (string $s): string => 2, 'a string', 2,
        ];
    }

    #[DataProvider('provFromExpectedTypeAndActualValue')]
    public function testFromExpectedTypeAndActualValue(mixed $function, string $expected, mixed $actual): void
    {
        $name = self::getFunctionName($function);
        $actualType = is_object($actual) ? 'object' : gettype($actual);
        $message = sprintf('Return value of %s() must be of the type %s, %s returned', $name, $expected, $actualType);

        $exception = InvalidReturnValueException::fromExpectedTypeAndActualValue($name, $expected, $actual);
        self::assertSame($message, $exception->getMessage());
    }

    protected static function getFunctionName(mixed $function): string
    {
        if (is_string($function)) {
            $name = $function;
        } elseif (is_array($function)) {
            $name = sprintf('%s::%s', is_object($function[0]) ? $function[0]::class : $function[0], $function[1]);
        } else {
            is_callable($function, true, $name);
        }

        return $name;
    }
}

// vim: syntax=php sw=4 ts=4 et:
