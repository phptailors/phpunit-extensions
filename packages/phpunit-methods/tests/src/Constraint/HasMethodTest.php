<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\HasMethod
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class HasMethodTest extends TestCase
{
    use ProvHasMethodTrait;

    public static function createConstraint(mixed ...$args): Constraint
    {
        return HasMethod::create(...$args);
    }

    /**
     * Returns constraint's class name.
     *
     * @psalm-return class-string<ConstraintClass>
     *
     * @psalm-pure
     */
    public static function getConstraintClass(): string
    {
        return HasMethod::class;
    }

    /**
     * @dataProvider provHasMethodSucceeds
     */
    public function testHasMethodMatchSucceeds(string $method, mixed $subject): void
    {
        parent::examineConstraintMatchSucceeds([$method], $subject);
    }

    /**
     * @dataProvider provHasMethodSucceeds
     */
    public function testNotHasMethodMatchFails(string $method, mixed $subject, string $string): void
    {
        parent::examineNotConstraintMatchFails([$method], $subject, $string);
    }

    /**
     * @dataProvider provHasMethodFails
     */
    public function testHasMethodMatchFails(string $method, mixed $subject, string $string): void
    {
        parent::examineConstraintMatchFails([$method], $subject, $string);
    }

    /**
     * @dataProvider provHasMethodFails
     */
    public function testNotHasMethodMatchSucceeds(string $method, mixed $subject): void
    {
        parent::examineNotConstraintMatchSucceeds([$method], $subject);
    }

    public function testCreateThrowsInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Argument 1 passed to '.HasMethod::class.'::create() must be method specification,'.
            ' \'public function foo??\' (syntax error at "??") given.'
        );

        HasMethod::create('public function foo??');
    }
}

// vim: syntax=php sw=4 ts=4 et:
