<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

interface InterfaceWithMethodFooV4C0Z
{
    /** @psalm-suppress PossiblyUnusedMethod */
    public function foo(): void;
}

final class ClassWithMethodFooV4C0Z
{
    // @codeCoverageIgnoreStart
    /** @psalm-suppress PossiblyUnusedMethod */
    public function foo(): void {}
    // @codeCoverageIgnoreEnd
}

trait TraitWithMethodFooV4C0Z
{
    // @codeCoverageIgnoreStart
    /** @psalm-suppress PossiblyUnusedMethod */
    public function foo(): void {}
    // @codeCoverageIgnoreEnd
}

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
trait ProvHasMethodTrait
{
    /**
     * @psalm-return iterable<string, array{
     *      method: string,
     *      subject: mixed,
     *      message: string
     *  }>
     *
     * @codeCoverageIgnore
     */
    public static function provHasMethod(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'method'  => 'foo',
            'subject' => InterfaceWithMethodFooV4C0Z::class,
            'message' => "Failed asserting that '".InterfaceWithMethodFooV4C0Z::class."' does not have method foo().",
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'method'  => 'foo',
            'subject' => ClassWithMethodFooV4C0Z::class,
            'message' => "Failed asserting that '".ClassWithMethodFooV4C0Z::class."' does not have method foo().",
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'method'  => 'foo',
            'subject' => TraitWithMethodFooV4C0Z::class,
            'message' => "Failed asserting that '".TraitWithMethodFooV4C0Z::class."' does not have method foo().",
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'method'  => 'public function foo',
            'subject' => TraitWithMethodFooV4C0Z::class,
            'message' => "Failed asserting that '".TraitWithMethodFooV4C0Z::class."' does not have public method foo().",
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'method'  => 'foo',
            'subject' => new ClassWithMethodFooV4C0Z(),
            'message' => ClassWithMethodFooV4C0Z::class.' Object',
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      method: string,
     *      subject: mixed,
     *      message: string
     *  }>
     *
     * @codeCoverageIgnore
     */
    public static function provNotHasMethod(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'method'  => 'bar',
            'subject' => InterfaceWithMethodFooV4C0Z::class,
            'message' => "Failed asserting that '".InterfaceWithMethodFooV4C0Z::class."' has method bar().",
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'method'  => 'bar',
            'subject' => ClassWithMethodFooV4C0Z::class,
            'message' => "Failed asserting that '".ClassWithMethodFooV4C0Z::class."' has method bar().",
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'method'  => 'bar',
            'subject' => TraitWithMethodFooV4C0Z::class,
            'message' => "Failed asserting that '".TraitWithMethodFooV4C0Z::class."' has method bar().",
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'method'  => 'private function foo',
            'subject' => TraitWithMethodFooV4C0Z::class,
            'message' => "Failed asserting that '".TraitWithMethodFooV4C0Z::class."' has private method foo().",
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'method'  => 'bar',
            'subject' => new ClassWithMethodFooV4C0Z(),
            'message' => ClassWithMethodFooV4C0Z::class.' Object',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'method'  => 'foo',
            'subject' => 123,
            'message' => 'Failed asserting that 123 has method foo()',
        ];
    }
}

// vim: syntax=php sw=4 ts=4 et:
