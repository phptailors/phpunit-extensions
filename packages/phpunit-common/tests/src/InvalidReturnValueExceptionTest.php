<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\InvalidReturnValueException
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type FunctionSpec = array{0:object|string,1:string}|\Closure|string|int|null
 */
final class InvalidReturnValueExceptionTest extends TestCase
{
    /**
     * @psalm-return iterable<string, list{FunctionSpec, string, string}>
     */
    public static function provFromExpectedAndActual(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'sprintf', 'a string', 'integer',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'inexistentFunction', 'a string', 'integer',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            /** @psalm-suppress InvalidReturnStatement,InvalidReturnType */
            function (): string { return 2; }, 'a string', 'integer',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            123, 'a string', 'integer',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            null, 'a string', 'integer',
        ];
    }

    /**
     * @dataProvider provFromExpectedAndActual
     *
     * @param mixed $function
     *
     * @psalm-param FunctionSpec $function
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFromExpectedAndActual($function, string $expected, string $actual): void
    {
        $name = self::getFunctionName($function);
        $message = sprintf('Return value of %s() must be %s, %s returned', $name, $expected, $actual);

        $exception = InvalidReturnValueException::fromExpectedAndActual($name, $expected, $actual);
        self::assertSame($message, $exception->getMessage());
    }

    /**
     * @psalm-return iterable<string, list{FunctionSpec, string, mixed}>
     */
    public static function provFromExpectedTypeAndActualValue(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'sprintf', 'string', 123,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'inexistentFunction', 'string', 123,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            [self::class, 'provFromExpectedTypeAndActualValue'], 'string', null,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            [self::class, 'inexistentMethod'], 'string', null,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            '', 'string', new \stdClass(),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            /** @psalm-suppress InvalidReturnStatement,InvalidReturnType */
            function (): string { return 2; }, 'a string', 2,
        ];
    }

    /**
     * @dataProvider provFromExpectedTypeAndActualValue
     *
     * @param mixed $function
     * @param mixed $actual
     *
     * @psalm-param FunctionSpec $function
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFromExpectedTypeAndActualValue($function, string $expected, $actual): void
    {
        $name = self::getFunctionName($function);
        $actualType = is_object($actual) ? 'object' : gettype($actual);
        $message = sprintf('Return value of %s() must be of the type %s, %s returned', $name, $expected, $actualType);

        $exception = InvalidReturnValueException::fromExpectedTypeAndActualValue($name, $expected, $actual);
        self::assertSame($message, $exception->getMessage());
    }

    /**
     * @param mixed $function
     *
     * @psalm-param FunctionSpec $function
     */
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
