<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Inheritance;

use Tailors\PHPUnit\InvalidReturnValueException;
use Tailors\PHPUnit\StringArgumentValidator;

/**
 * Implementation of an inheritance constraint class.
 *
 * The trait expects the following static attributes to be defined::
 *
 *      private static $verb;           // for example $verb = 'extends class';
 *      private static $negatedVerb;    // for example $negatedVerb = 'does not extend class';
 *      private static $validation;     // for example $validation = ['class_exists', 'a class-string'];
 *      private static $inheritance;    // for example $validation = 'class_parents';
 *      private static $supports;       // for example $supports = ['class_exists'];
 *
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
trait ConstraintImplementationTrait
{
    /**
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     *
     * @psalm-assert class-string $expected
     */
    public static function create(string $expected): self
    {
        self::getValidator()->validate(1, $expected);

        return new self($expected);
    }

    /**
     * Returns short description of what we examine, e.g. ``'impements interface'``.
     */
    protected function verb(): string
    {
        return self::$verb;
    }

    /**
     * Returns short negated description of what we examine, e.g. ``'does not impement interface'``.
     */
    protected function negatedVerb(): string
    {
        return self::$negatedVerb;
    }

    /**
     * @throws InvalidReturnValueException
     *
     * @psalm-return array<string>
     */
    protected function inheritance(string $class): array
    {
        $value = (self::$inheritance)($class);
        self::assertReturnValueIsListOfStrings([self::class, '$inheritance'], $value);

        return $value;
    }

    /**
     * Checks if *$subject* may be used as an argument to inheritance().
     */
    protected function supports(string $subject): bool
    {
        foreach (self::$supports as $function) {
            if (call_user_func($function, $subject)) {
                return true;
            }
        }

        return false;
    }

    private static function getValidator(): StringArgumentValidator
    {
        return new StringArgumentValidator(self::$validation[0], self::$validation[1]);
    }

    /**
     * @param mixed $function
     * @param mixed $value
     *
     * @psalm-template ValueType $value
     *
     * @psalm-param ValueType $value
     *
     * @param-out ValueType $value
     *
     * @psalm-assert array<string> $value
     *
     * @throws InvalidReturnValueException
     */
    private static function assertReturnValueIsListOfStrings($function, &$value): void
    {
        if (!is_array($value)) {
            throw InvalidReturnValueException::fromExpectedTypeAndActualValue($function, 'array', $value);
        }

        $strings = array_filter($value, 'is_string');

        if (count($strings) < count($value)) {
            throw InvalidReturnValueException::fromExpectedTypeAndActualValue($function, 'array of strings', $value);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
