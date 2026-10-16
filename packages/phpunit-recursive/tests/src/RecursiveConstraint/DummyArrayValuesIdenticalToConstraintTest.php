<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveConstraint\DummyArrayValuesIdenticalToConstraint
 * @covers \Tailors\PHPUnit\RecursiveConstraint\DummyArrayValuesIdenticalToConstraintTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type CtorArgs = list{ArrayLike}
 *
 * @template-extends AbstractRecursiveConstraintTestCase<DummyArrayValuesIdenticalToConstraint>
 *
 * @var TypesInterface $__phpstan__workaround__unused_import__TypesInterface
 */
final class DummyArrayValuesIdenticalToConstraintTest extends AbstractRecursiveConstraintTestCase
{
    /**
     * @psalm-param CtorArgs $ctorArgs
     */
    public static function createConstraint(array $ctorArgs): Constraint
    {
        return DummyArrayValuesIdenticalToConstraint::create(...$ctorArgs);
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
            'ctorArgs' => [['b' => 'B']],
            'actual'   => ['a' => 'A', 'b' => 'B', 'c' => 'C'],
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
            'ctorArgs' => [['x' => 'X']],
            'actual'   => ['a' => 'A', 'b' => 'B', 'c' => 'C'],
            'message'  => self::message('array'),
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
            'ctorArgs' => [['x' => 'X']],
            'actual'   => ['a' => 'A', 'b' => 'B', 'c' => 'C'],
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
            'ctorArgs' => [['b' => 'B']],
            'actual'   => ['a' => 'A', 'b' => 'B', 'c' => 'C'],
            'message'  => self::message('array', true),
        ];
    }

    /**
     * @codeCoverageIgnore Called from providers only.
     */
    public static function complement(): string
    {
        return 'an array with values identical to the specified ones';
    }
}
// vim: syntax=php sw=4 ts=4 et:
