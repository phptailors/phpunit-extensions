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
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Methods\MethodSpecFactory;
use Tailors\PHPUnit\Methods\MethodSpecInterface;
use Tailors\PHPUnit\Methods\MethodSpecSyntaxError;

/**
 * Constraint that accepts objects, classes, traits and interfaces having given method.
 */
final class HasMethod extends Constraint
{
    /**
     * @var MethodSpecInterface
     */
    private $methodSpec;

    public function __construct(MethodSpecInterface $methodSpec)
    {
        $this->methodSpec = $methodSpec;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function create(string $methodSpecString): self
    {
        $factory = new MethodSpecFactory();

        try {
            $methodSpec = $factory->fromString($methodSpecString);
        } catch (MethodSpecSyntaxError $error) {
            $actual = sprintf('%s (%s)', var_export($methodSpecString, true), $error->getMessage());

            throw InvalidArgumentException::fromBacktrace(1, 'method specification', $actual, 1);
        }

        return new self($methodSpec);
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string
    {
        return sprintf('has %s()', $this->methodSpec->toString());
    }

    /**
     * @param mixed $other
     *
     * @assert-if-true object|class-string|trait-string|interface-string $other
     */
    final protected function matches($other): bool
    {
        if (!$this->ensureCanReflectAsClass($other)) {
            return false;
        }

        try {
            $method = new \ReflectionMethod($other, $this->methodSpec->getName());
        } catch (\ReflectionException $exception) {
            return false;
        }

        return $this->methodSpec->matches($method);
    }

    /**
     * @param mixed $other
     *
     * @psalm-assert-if-true object|class-string|trait-string|interface-string $other
     */
    private function ensureCanReflectAsClass($other): bool
    {
        return is_object($other) || (is_string($other) && (
            interface_exists($other) || class_exists($other) || trait_exists($other)
        ));
    }
}

// vim: syntax=php sw=4 ts=4 et:
