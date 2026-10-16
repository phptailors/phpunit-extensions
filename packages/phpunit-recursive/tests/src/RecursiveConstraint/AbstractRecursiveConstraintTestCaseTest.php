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
 * @covers \Tailors\PHPUnit\RecursiveConstraint\AbstractRecursiveConstraintTestCase
 * @covers \Tailors\PHPUnit\RecursiveConstraint\AbstractRecursiveConstraintTestCaseTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type ConstraintClass = ExampleRecursiveConstraint
 * @psalm-type CtorArgs        = list{ArrayLike}
 *
 * @template-extends AbstractRecursiveConstraintTestCase<DummyArrayValuesIdenticalToConstraint>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class AbstractRecursiveConstraintTestCaseTest extends AbstractRecursiveConstraintTestCase
{
    /**
     * @codeCoverageIgnore Called from providers only.
     */
    public static function complement(): string
    {
        return 'an array with values identical to the specified ones';
    }

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param CtorArgs $ctorArgs
     */
    public static function createConstraint(array $ctorArgs): Constraint
    {
        return DummyArrayValuesIdenticalToConstraint::create(...$ctorArgs);
    }

    /**
     * @psalm-return iterable<string, array{
     *      expect: ArrayLike,
     *      actual: mixed
     * }>
     *
     * @codeCoverageIgnore
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
     *
     * @codeCoverageIgnore
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
     *
     * @codeCoverageIgnore
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
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provConstraintEvaluationSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provArrayValuesIdenticalTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'ctorArgs' => [$case['expect']],
                'actual'   => $case['actual'],
            ];
        }
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
        $cases = (function (): iterable {
            yield from self::provArrayValuesEqualButNotIdenticalTo();

            yield from self::provArrayValuesNotEqualTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'ctorArgs' => [$case['expect']],
                'actual'   => $case['actual'],
                'message'  => self::message('array'),
            ];
        }
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
        $cases = (function (): iterable {
            yield from self::provArrayValuesEqualButNotIdenticalTo();

            yield from self::provArrayValuesNotEqualTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'ctorArgs' => [$case['expect']],
                'actual'   => $case['actual'],
            ];
        }
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
        $cases = (function (): iterable {
            yield from self::provArrayValuesIdenticalTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'ctorArgs' => [$case['expect']],
                'actual'   => $case['actual'],
                'message'  => self::message('array', true),
            ];
        }
    }
}
// vim: syntax=php sw=4 ts=4 et:
