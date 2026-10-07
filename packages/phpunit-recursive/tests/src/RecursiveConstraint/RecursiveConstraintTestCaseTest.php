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
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveConstraint\ExampleRecursiveConstraint
 * @covers \Tailors\PHPUnit\RecursiveConstraint\RecursiveConstraintTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type ConstraintClass      = ExampleRecursiveConstraint
 * @psalm-type CreateConstraintArgs = list{ArrayLike}
 *
 * @template-extends RecursiveConstraintTestCase<ExampleRecursiveConstraint>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class RecursiveConstraintTestCaseTest extends RecursiveConstraintTestCase
{
    public static function subject(): string
    {
        return 'an array';
    }

    public static function selectable(): string
    {
        return 'values';
    }

    public static function adjective(): string
    {
        return 'identical to';
    }

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param CreateConstraintArgs $args
     */
    public static function createConstraint(array $args): Constraint
    {
        return ExampleRecursiveConstraint::create(...$args);
    }

    /**
     * @psalm-external-mutation-free
     */
    public static function getConstraintClass(): string
    {
        return ExampleRecursiveConstraint::class;
    }

    /**
     * @psalm-return iterable<string, array{
     *      expect: ArrayLike,
     *      actual: mixed
     * }>
     */
    public static function provArrayValuesIdenticalTo(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'foo' => 'FOO',
            ],
            'actual' => [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ],
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      expect: ArrayLike,
     *      actual: mixed
     * }>
     */
    public static function provArrayValuesEqualButNotIdenticalTo(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'foo' => '',
            ],
            'actual' => [
                'foo' => null,
            ],
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      expect: ArrayLike,
     *      actual: mixed
     * }>
     */
    public static function provArrayValuesNotEqualTo(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'foo' => 7,
            ],
            'actual' => [
                'foo' => 11,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'foo' => 7,
                'bar' => 'BAR',
            ],
            'actual' => [
                'foo' => 7,
            ],
        ];
    }

    /**
     * @param mixed $actual
     *
     * @dataProvider provArrayValuesIdenticalTo
     *
     * @psalm-param ArrayLike $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testValuesMatchSucceeds(iterable $expect, $actual): void
    {
        $this->examineValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @param mixed $actual
     *
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     * @dataProvider provArrayValuesNotEqualTo
     *
     * @psalm-param ArrayLike $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testValuesMatchFails(iterable $expect, $actual): void
    {
        $this->examineValuesMatchFails($expect, $actual, 'array');
    }

    /**
     * @param mixed $actual
     *
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     * @dataProvider provArrayValuesNotEqualTo
     *
     * @psalm-param ArrayLike $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotValuesMatchSucceeds(iterable $expect, $actual): void
    {
        $this->examineNotValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @param mixed $actual
     *
     * @dataProvider provArrayValuesIdenticalTo
     *
     * @psalm-param ArrayLike $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotValuesMatchFails(iterable $expect, $actual): void
    {
        $this->examineNotValuesMatchFails($expect, $actual, 'array');
    }
}
// vim: syntax=php sw=4 ts=4 et:
