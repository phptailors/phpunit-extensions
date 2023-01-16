<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

interface InterfaceWithMethodFooV4C0Z
{
    public function foo(): void;
}

final class ClassWithMethodFooV4C0Z
{
    // @codeCoverageIgnoreStart
    public function foo(): void
    {
    }
    // @codeCoverageIgnoreEnd
}

trait TraitWithMethodFooV4C0Z
{
    // @codeCoverageIgnoreStart
    public function foo(): void
    {
    }
    // @codeCoverageIgnoreEnd
}

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
trait ProvHasMethodTrait
{
    // @codeCoverageIgnoreStart

    /**
     * @psalm-return array<array{0:string,1:mixed,2:string}>
     */
    public function provHasMethodSucceeds(): array
    {
        return [
            [
                'foo',
                InterfaceWithMethodFooV4C0Z::class,
                "Failed asserting that '".InterfaceWithMethodFooV4C0Z::class."' does not have method foo().",
            ],
            [
                'foo',
                ClassWithMethodFooV4C0Z::class,
                "Failed asserting that '".ClassWithMethodFooV4C0Z::class."' does not have method foo().",
            ],
            [
                'foo',
                TraitWithMethodFooV4C0Z::class,
                "Failed asserting that '".TraitWithMethodFooV4C0Z::class."' does not have method foo().",
            ],
            [
                'public function foo',
                TraitWithMethodFooV4C0Z::class,
                "Failed asserting that '".TraitWithMethodFooV4C0Z::class."' does not have public method foo().",
            ],
            [
                'foo',
                new ClassWithMethodFooV4C0Z(),
                ClassWithMethodFooV4C0Z::class.' Object',
            ],
        ];
    }

    /**
     * @psalm-return array<array{0:string,1:mixed,2:string}>
     */
    public function provHasMethodFails(): array
    {
        return [
            [
                'bar',
                InterfaceWithMethodFooV4C0Z::class,
                "Failed asserting that '".InterfaceWithMethodFooV4C0Z::class."' has method bar().",
            ],
            [
                'bar', ClassWithMethodFooV4C0Z::class,
                "Failed asserting that '".ClassWithMethodFooV4C0Z::class."' has method bar().",
            ],
            [
                'bar', TraitWithMethodFooV4C0Z::class,
                "Failed asserting that '".TraitWithMethodFooV4C0Z::class."' has method bar().",
            ],
            [
                'private function foo', TraitWithMethodFooV4C0Z::class,
                "Failed asserting that '".TraitWithMethodFooV4C0Z::class."' has private method foo().",
            ],
            [
                'bar',
                new ClassWithMethodFooV4C0Z(),
                ClassWithMethodFooV4C0Z::class.' Object',
            ],
            [
                'foo',
                123,
                'Failed asserting that 123 has method foo()',
            ],
        ];
    }

    // @codeCoverageIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
