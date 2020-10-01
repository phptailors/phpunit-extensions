<?php

declare(strict_types=1);

/*
 * This file is part of php-fox/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace PHPFox\PHPUnit\Constraint;

use PHPFox\PHPUnit\InvalidArgumentException;

/**
 * Constraint that accepts classes that extend given class.
 *
 * @author Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 */
final class UsesTrait extends AbstractInheritanceConstraint
{
    /**
     * @throws InvalidArgumentException
     *
     * @psalm-assert trait-string $expected
     */
    public static function fromTraitString(string $expected): self
    {
        if (!trait_exists($expected)) {
            $provided = sprintf("'%s'", addslashes($expected));

            throw InvalidArgumentException::fromBackTrace(1, 'a trait-string', $provided);
        }

        return new self($expected);
    }

    /**
     * Returns short description of what we examine, e.g. ``'impements interface'``.
     */
    protected function verb(bool $negated = false): string
    {
        if ($negated) {
            return 'does not use trait';
        }

        return 'uses trait';
    }

    /**
     * Returns an array of "inherited classes" -- eiher interfaces *$class*
     * implements, parent classes it extends or traits it uses, depending on
     * the actual implementation of this constraint.
     */
    protected function inheritance(string $class): array
    {
        return class_uses($class);
    }

    /**
     * Checks if *$class* may be used as an argument to ``getInheritedClassesFor()``.
     *
     * @psalm-assert-if-true class-string|trait-string $class
     */
    protected function supportsActual(string $class): bool
    {
        return class_exists($class) || trait_exists($class);
    }
}

// vim: syntax=php sw=4 ts=4 et:
