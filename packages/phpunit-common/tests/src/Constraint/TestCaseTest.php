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
use PHPUnit\Framework\Constraint\IsTrue;
use PHPUnit\Framework\Constraint\LogicalOr;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\TestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-extends TestCase<Constraint, list{}>
 */
final class TestCaseTest extends TestCase
{
    /**
     * @psalm-external-mutation-free
     *
     * @palm-param list{} $args
     */
    public static function createConstraint(array $args): Constraint
    {
        return LogicalOr::fromConstraints(new IsTrue(), new IsTrue());
    }

    /**
     * @psalm-external-mutation-free
     */
    public static function getConstraintClass(): string
    {
        return LogicalOr::class;
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCreateConstraint(): void
    {
        $constraint = $this->examineCreateConstraint([]);
        $this->assertInstanceOf(Constraint::class, $constraint);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstraintUnaryOperatorFailure(): void
    {
        $this->examineConstraintUnaryOperatorFailure([], false, 'Failed asserting that noop( false is true or is true )');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstraintMatchSucceeds(): void
    {
        $this->examineConstraintMatchSucceeds([], true);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstraintMatchFails(): void
    {
        $this->examineConstraintMatchFails([], false, 'Failed asserting that false is true or is true.');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotConstraintMatchSucceeds(): void
    {
        $this->examineNotConstraintMatchSucceeds([], false);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotConstraintMatchFails(): void
    {
        $this->examineNotConstraintMatchFails([], true, 'Failed asserting that not( true is true or is true )');
    }
}
// vim: syntax=php sw=4 ts=4 et:
