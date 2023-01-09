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

    public static function getConstraintClass(): string
    {
        return HasMethod::class;
    }

    public function createConstraint(...$args): Constraint
    {
        return new HasMethod(...$args);
    }

    public function testCreateConstraint(): void
    {
        $this->examineCreateConstraint(['foo']);
    }

    /**
     * @dataProvider provHasMethodSucceeds
     *
     * @param mixed $actual
     * @param mixed $subject
     */
    public function testHasMethodMatchSucceeds(string $method, $subject): void
    {
        parent::examineConstraintMatchSucceeds([$method], $subject);
    }

    /**
     * @dataProvider provHasMethodSucceeds
     *
     * @param mixed $actual
     * @param mixed $subject
     */
    public function testNotHasMethodMatchFails(string $method, $subject, string $string): void
    {
        parent::examineNotConstraintMatchFails([$method], $subject, $string);
    }

    /**
     * @dataProvider provHasMethodFails
     *
     * @param mixed $actual
     * @param mixed $subject
     */
    public function testHasMethodMatchFails(string $method, $subject, string $string): void
    {
        parent::examineConstraintMatchFails([$method], $subject, $string);
    }

    /**
     * @dataProvider provHasMethodFails
     *
     * @param mixed $actual
     * @param mixed $subject
     */
    public function testNotHasMethodMatchSucceeds(string $method, $subject): void
    {
        parent::examineNotConstraintMatchSucceeds([$method], $subject);
    }
}

// vim: syntax=php sw=4 ts=4 et:
