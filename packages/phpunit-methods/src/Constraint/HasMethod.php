<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

/**
 * Constraint that accepts objects, classes, traits and interfaces having given method.
 */
final class HasMethod extends Constraint
{
    /**
     * @var string
     *
     * @psalm-readonly
     */
    private $method;

    public function __construct(string $method)
    {
        $this->method = $method;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string
    {
        return sprintf('has method \'%s()\'', $this->method);
    }

    /**
     * @param mixed $other
     */
    final protected function matches($other): bool
    {
        if (!$this->ensureCanReflectAsClass($other)) {
            return false;
        }

        $class = new \ReflectionClass($other);

        return $class->hasMethod($this->method);
    }

    /**
     * @psalm-assert-if-true object|class-string|trait-string|interface-string $other
     *
     * @param mixed $other
     */
    private function ensureCanReflectAsClass($other): bool
    {
        return is_object($other) || (is_string($other) && (
            interface_exists($other) || class_exists($other) || trait_exists($other)
        ));
    }
}

// vim: syntax=php sw=4 ts=4 et:
