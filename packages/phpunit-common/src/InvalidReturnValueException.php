<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class InvalidReturnValueException extends \LogicException implements ExceptionInterface
{
    /**
     * @param mixed  $function
     * @param string $expectedType
     * @param mixed  $actualValue  Actual value
     *
     * @psalm-template ActualType
     *
     * @psalm-param ActualType $actualValue
     *
     * @param-out ActualType $actualValue
     */
    public static function fromExpectedTypeAndActualValue($function, string $expectedType, &$actualValue): self
    {
        $actualType = is_object($actualValue) ? 'object' : gettype($actualValue);

        return self::fromExpectedAndActual($function, sprintf('of the type %s', $expectedType), $actualType);
    }

    /**
     * @param mixed  $function
     * @param string $expected
     * @param string $actual
     */
    public static function fromExpectedAndActual($function, string $expected, string $actual): self
    {
        is_callable($function, true, $name);

        return new self(sprintf('Return value of %s() must be %s, %s returned', $name, $expected, $actual));
    }
}

// vim: syntax=php sw=4 ts=4 et:
