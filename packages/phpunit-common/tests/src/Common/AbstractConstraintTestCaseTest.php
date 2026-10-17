<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsTrue;
use PHPUnit\Framework\Constraint\LogicalOr;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Common\AbstractConstraintTestCase
 * @covers \Tailors\PHPUnit\Common\AbstractConstraintTestCaseTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs = list{}
 *
 * @template-extends AbstractConstraintTestCase<Constraint, list{}>
 */
final class AbstractConstraintTestCaseTest extends AbstractConstraintTestCase
{
    /**
     * @psalm-external-mutation-free
     *
     * @psalm-param list{} $ctorArgs
     */
    public static function createConstraint(array $ctorArgs): Constraint
    {
        return LogicalOr::fromConstraints(new IsTrue(), new IsTrue());
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provConstraintUnaryOperatorFailure(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'actual'   => false,
            'message'  => 'Failed asserting that noop( false is true or is true )',
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provConstraintEvaluationSucceeds(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'actual'   => true,
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provConstraintEvaluationFails(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'actual'   => false,
            'message'  => 'Failed asserting that false is true or is true.',
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provNotConstraintEvaluationSucceeds(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'actual'   => false,
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provNotConstraintEvaluationFails(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'actual'   => true,
            'message'  => 'Failed asserting that not( true is true or is true ).',
        ];
    }
}
// vim: syntax=php sw=4 ts=4 et:
