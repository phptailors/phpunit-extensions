<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\Constraint;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(ConstraintTestCase::class)]
#[CoversClass(ExampleConstraint::class)]
final class ConstraintTestCaseTest extends ConstraintTestCase
{
    public static function subject(): string
    {
        return 'an array or ArrayAccess';
    }

    public static function selectable(): string
    {
        return 'values';
    }

    public static function adjective(): string
    {
        return 'identical to';
    }

    public static function createConstraint(...$args): Constraint
    {
        return ExampleConstraint::create(...$args);
    }

    public static function getConstraintClass(): string
    {
        return ExampleConstraint::class;
    }

    public static function provArrayValuesIdenticalTo(): array
    {
        return [
            'ConstraintTestCaseTest.php:'.__LINE__ => [
                'expect' => [
                    'foo' => 'FOO',
                ],
                'actual' => [
                    'foo' => 'FOO',
                    'bar' => 'BAR',
                ],
            ],
        ];
    }

    public static function provArrayValuesEqualButNotIdenticalTo(): array
    {
        return [
            'ConstraintTestCaseTest.php:'.__LINE__ => [
                'expect' => [
                    'foo' => '',
                ],
                'actual' => [
                    'foo' => null,
                ],
            ],
        ];
    }

    public static function provArrayValuesNotEqualTo(): array
    {
        return [
            'ConstraintTestCaseTest.php:'.__LINE__ => [
                'expect' => [
                    'foo' => 7,
                ],
                'actual' => [
                    'foo' => 11,
                ],
            ],
        ];
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesIdenticalTo')]
    public function testValuesMatchSucceeds(array $expect, $actual): void
    {
        $this->examineValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    #[DataProvider('provArrayValuesNotEqualTo')]
    public function testValuesMatchFails(array $expect, $actual): void
    {
        $this->examineValuesMatchFails($expect, $actual, 'array');
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    #[DataProvider('provArrayValuesNotEqualTo')]
    public function testNotValuesMatchSucceeds(array $expect, $actual): void
    {
        $this->examineNotValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provArrayValuesIdenticalTo')]
    public function testNotValuesMatchFails(array $expect, $actual): void
    {
        $this->examineNotValuesMatchFails($expect, $actual, 'array');
    }
}
// vim: syntax=php sw=4 ts=4 et:
