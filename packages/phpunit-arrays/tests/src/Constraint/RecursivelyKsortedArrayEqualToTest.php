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
use Tailors\PHPUnit\Arrays\KsortedConstraintTestCase;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\KsortedConstraintTestCase
 * @covers \Tailors\PHPUnit\Constraint\RecursivelyKsortedArrayEqualTo
 * @covers \Tailors\PHPUnit\Constraint\RecursivelyKsortedArrayEqualToTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ConstraintClass = RecursivelyKsortedArrayEqualTo
 * @psalm-type CtorArgs        = list{0: array, 1?: int}
 *
 * @template-extends KsortedConstraintTestCase<RecursivelyKsortedArrayEqualTo>
 */
final class RecursivelyKsortedArrayEqualToTest extends KsortedConstraintTestCase
{
    use ProvRecursivelyKsortedArrayTrait;

    /**
     * @codeCoverageIgnore Called only from providers.
     */
    public static function adjective(): string
    {
        return 'equal to';
    }

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param CtorArgs $ctorArgs
     */
    public static function createConstraint(array $ctorArgs): Constraint
    {
        return RecursivelyKsortedArrayEqualTo::create(...$ctorArgs);
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
            yield from self::provRecursivelyKsortedArrayIdenticalTo();

            yield from self::provRecursivelyKsortedArrayEqualButNotIdenticalTo();
        })();

        foreach ($cases as $key => $case) {
            $ctorArgs = array_key_exists('flags', $case) ? [$case['expect'], $case['flags']] : [$case['expect']];

            yield $key => [
                'ctorArgs' => $ctorArgs,
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
            yield from self::provRecursivelyKsortedArrayNotEqualTo();

            yield from self::provRecursivelyKsortedArrayNotEqualToNonArray();
        })();

        foreach ($cases as $key => $case) {
            $ctorArgs = array_key_exists('flags', $case) ? [$case['expect'], $case['flags']] : [$case['expect']];

            yield $key => [
                'ctorArgs' => $ctorArgs,
                'actual'   => $case['actual'],
                'message'  => self::message($case['string']),
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
            yield from self::provRecursivelyKsortedArrayNotEqualTo();

            yield from self::provRecursivelyKsortedArrayNotEqualToNonArray();
        })();

        foreach ($cases as $key => $case) {
            $ctorArgs = array_key_exists('flags', $case) ? [$case['expect'], $case['flags']] : [$case['expect']];

            yield $key => [
                'ctorArgs' => $ctorArgs,
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
            yield from self::provRecursivelyKsortedArrayIdenticalTo();

            yield from self::provRecursivelyKsortedArrayEqualButNotIdenticalTo();
        })();

        foreach ($cases as $key => $case) {
            $ctorArgs = array_key_exists('flags', $case) ? [$case['expect'], $case['flags']] : [$case['expect']];

            yield $key => [
                'ctorArgs' => $ctorArgs,
                'actual'   => $case['actual'],
                'message'  => self::message($case['string'], true),
            ];
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
