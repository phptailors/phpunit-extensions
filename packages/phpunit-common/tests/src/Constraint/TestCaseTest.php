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
use PHPUnit\Framework\Constraint\IsTrue;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\TestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class TestCaseTest extends TestCase
{
    public function createConstraint(...$args): Constraint
    {
        return new IsTrue(...$args);
    }

    public static function getConstraintClass(): string
    {
        return IsTrue::class;
    }

    public function testCreateConstraint(): void
    {
        $constraint = $this->examineCreateConstraint([]);
        $this->assertInstanceOf(Constraint::class, $constraint);
    }

    public function testConstraintUnaryOperatorFailure(): void
    {
        $this->examineConstraintUnaryOperatorFailure([], false, 'Failed asserting that false is true');
    }

    public function testConstraintMatchSucceeds(): void
    {
        $this->examineConstraintMatchSucceeds([], true);
    }

    public function testConstraintMatchFails(): void
    {
        $this->examineConstraintMatchFails([], false, 'Failed asserting that false is true');
    }

    public function testNotConstraintMatchSucceeds(): void
    {
        $this->examineNotConstraintMatchSucceeds([], false);
    }

    public function testNotConstraintMatchFails(): void
    {
        $this->examineNotConstraintMatchFails([], true, 'Failed asserting that true is not true');
    }
}
// vim: syntax=php sw=4 ts=4 et:
