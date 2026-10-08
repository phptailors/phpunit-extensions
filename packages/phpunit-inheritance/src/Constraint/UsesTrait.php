<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Inheritance\AbstractInheritanceConstraint;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\InvalidReturnValueException;
use Tailors\PHPUnit\StringArgumentValidator;

/**
 * Constraint that accepts classes that extend given class.
 */
final class UsesTrait extends AbstractInheritanceConstraint
{
    /**
     * @throws InvalidArgumentException
     *
     * @psalm-assert class-string $expected
     */
    public static function create(string $expected): self
    {
        (new StringArgumentValidator('trait_exists', 'a trait-string'))->validate(1, $expected);

        return new self($expected);
    }

    protected function verb(): string
    {
        return 'uses trait';
    }

    protected function negatedVerb(): string
    {
        return 'does not use trait';
    }

    /**
     * @throws InvalidReturnValueException
     *
     * @psalm-return array<trait-string, trait-string>
     */
    protected function inheritance(string $class): array
    {
        $value = class_uses($class);

        return $value === false ? [] : $value;
    }

    protected function supports(string $subject): bool
    {
        return class_exists($subject) || trait_exists($subject);
    }
}

// vim: syntax=php sw=4 ts=4 et:
