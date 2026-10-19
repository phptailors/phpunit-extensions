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
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\Arrays\KsortedConstraintTestCase;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ConstraintClass = KsortedArrayEqualTo
 * @psalm-type CtorArgs        = list{0: array, 1?: int}
 *
 * @template-extends KsortedConstraintTestCase<KsortedArrayEqualTo>
 */
#[CoversClass(KsortedConstraintTestCase::class)]
#[CoversClass(KsortedArrayEqualTo::class)]
#[CoversClass(KsortedArrayEqualToTest::class)]
#[Small]
final class KsortedArrayEqualToTest extends KsortedConstraintTestCase
{
    use ProvKsortedArrayTrait;

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
        return KsortedArrayEqualTo::create(...$ctorArgs);
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
            yield from self::provKsortedArrayIdenticalTo();

            yield from self::provKsortedArrayEqualButNotIdenticalTo();
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
            yield from self::provKsortedArrayNotEqualTo();

            yield from self::provKsortedArrayNotEqualToNonArray();
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
            yield from self::provKsortedArrayNotEqualTo();

            yield from self::provKsortedArrayNotEqualToNonArray();
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
            yield from self::provKsortedArrayIdenticalTo();

            yield from self::provKsortedArrayEqualButNotIdenticalTo();
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
