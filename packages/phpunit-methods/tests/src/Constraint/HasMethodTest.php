<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(HasMethod::class)]
#[Small]
final class HasMethodTest extends TestCase
{
    use ProvHasMethodTrait;

    #[\Override]
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
    #[\Override]
    public static function getConstraintClass(): string
    {
        return HasMethod::class;
    }

    #[DataProvider('provHasMethodSucceeds')]
    public function testHasMethodMatchSucceeds(string $method, mixed $subject, string $_): void
    {
        parent::examineConstraintMatchSucceeds([$method], $subject);
    }

    #[DataProvider('provHasMethodSucceeds')]
    public function testNotHasMethodMatchFails(string $method, mixed $subject, string $string): void
    {
        parent::examineNotConstraintMatchFails([$method], $subject, $string);
    }

    #[DataProvider('provHasMethodFails')]
    public function testHasMethodMatchFails(string $method, mixed $subject, string $string): void
    {
        parent::examineConstraintMatchFails([$method], $subject, $string);
    }

    #[DataProvider('provHasMethodFails')]
    public function testNotHasMethodMatchSucceeds(string $method, mixed $subject, string $_): void
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
