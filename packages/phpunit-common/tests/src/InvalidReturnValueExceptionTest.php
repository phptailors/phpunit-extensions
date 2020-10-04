<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\TestCase;

/**
 * @small
 * @covers \Tailors\PHPUnit\InvalidReturnValueException
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class InvalidReturnValueExceptionTest extends TestCase
{
    public static function provFromExpectedAndActual(): array
    {
        return [
            'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
                'sprintf', 'a string', 'integer',
            ],

            'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
                'inexistentFunction', 'a string', 'integer',
            ],

            'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
                function (string $s): string { return 2; }, 'a string', 'integer',
            ],

            'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
                123, 'a string', 'integer',
            ],

            'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
                null, 'a string', 'integer',
            ],
        ];
    }

    /**
     * @dataProvider provFromExpectedAndActual
     *
     * @param array{0:string|object,1:string}|string|callable $function
     */
    public function testFromExpectedAndActual($function, string $expected, string $actual): void
    {
        $name = self::getFunctionName($function);
        $message = sprintf('Return value of %s() must be %s, %s returned', $name, $expected, $actual);

        $exception = InvalidReturnValueException::fromExpectedAndActual($name, $expected, $actual);
        self::assertSame($message, $exception->getMessage());
    }

    public static function provFromExpectedTypeAndActualValue(): array
    {
        return [
            'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
                'sprintf', 'string', 123,
            ],
            'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
                'inexistentFunction', 'string', 123,
            ],
            'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
                [self::class, 'provFromExpectedTypeAndActualValue'], 'string', null,
            ],
            'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
                [self::class, 'inexistentMethod'], 'string', null,
            ],
            'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
                '', 'string', new \stdClass(),
            ],

            'InvalidReturnValueExceptionTest.php:'.__LINE__ => [
                function (string $s): string { return 2; }, 'a string', 2,
            ],
        ];
    }

    /**
     * @dataProvider provFromExpectedTypeAndActualValue
     *
     * @param array{0:string|object,1:string}|string|callable $function
     * @param mixed                                           $actual
     */
    public function testFromExpectedTypeAndActualValue($function, string $expected, $actual): void
    {
        $name = self::getFunctionName($function);
        $actualType = is_object($actual) ? 'object' : gettype($actual);
        $message = sprintf('Return value of %s() must be of the type %s, %s returned', $name, $expected, $actualType);

        $exception = InvalidReturnValueException::fromExpectedTypeAndActualValue($name, $expected, $actual);
        self::assertSame($message, $exception->getMessage());
    }

    protected static function getFunctionName($function): string
    {
        if (is_string($function)) {
            $name = $function;
        } elseif (is_array($function)) {
            $name = sprintf('%s::%s', is_object($function[0]) ? get_class($function[0]) : $function[0], $function[1]);
        } else {
            is_callable($function, true, $name);
        }

        return $name;
    }
}

// vim: syntax=php sw=4 ts=4 et:
