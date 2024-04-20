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
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsTrue;
use PHPUnit\Framework\Constraint\LogicalOr;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(TestCase::class)]
final class TestCaseTest extends TestCase
{
    public static function createConstraint(...$args): Constraint
    {
        return LogicalOr::fromConstraints(new IsTrue(...$args), new IsTrue(...$args));
    }

    public static function getConstraintClass(): string
    {
        return LogicalOr::class;
    }

    public function testCreateConstraint(): void
    {
        $constraint = $this->examineCreateConstraint([]);
        $this->assertInstanceOf(Constraint::class, $constraint);
    }

    public function testConstraintUnaryOperatorFailure(): void
    {
        $this->examineConstraintUnaryOperatorFailure([], false, 'Failed asserting that noop( false is true or is true )');
    }

    public function testConstraintMatchSucceeds(): void
    {
        $this->examineConstraintMatchSucceeds([], true);
    }

    public function testConstraintMatchFails(): void
    {
        $this->examineConstraintMatchFails([], false, 'Failed asserting that false is true or is true.');
    }

    public function testNotConstraintMatchSucceeds(): void
    {
        $this->examineNotConstraintMatchSucceeds([], false);
    }

    public function testNotConstraintMatchFails(): void
    {
        $this->examineNotConstraintMatchFails([], true, 'Failed asserting that not( true is true or is true )');
    }
}
// vim: syntax=php sw=4 ts=4 et:
