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
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Properties\AbstractPropertiesConstraintTestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\ClassPropertiesIdenticalTo
 * @covers \Tailors\PHPUnit\Constraint\ClassPropertiesIdenticalToTest
 * @covers \Tailors\PHPUnit\Properties\AbstractPropertiesConstraintTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type ConstraintClass = ClassPropertiesIdenticalTo
 * @psalm-type CtorArgs        = list{ArrayLike}
 *
 * @template-extends AbstractPropertiesConstraintTestCase<ClassPropertiesIdenticalTo, list{ArrayLike}>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class ClassPropertiesIdenticalToTest extends AbstractPropertiesConstraintTestCase
{
    use ProvClassPropertiesTrait;

    /**
     * @codeCoverageIgnore Called from providers only.
     */
    public static function complement(): string
    {
        return 'a class with properties identical to the specified ones';
    }

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return ConstraintClass
     */
    public static function createConstraint(array $ctorArgs): Constraint
    {
        return ClassPropertiesIdenticalTo::create(...$ctorArgs);
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
            yield from self::provClassPropertiesIdenticalTo();
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
    final public static function provConstraintEvaluationFails(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provClassPropertiesNotEqualTo();

            yield from self::provClassPropertiesEqualButNotIdenticalTo();

            yield from self::provClassPropertiesNotEqualToNonClass();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'ctorArgs' => [$case['expect']],
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
            yield from self::provClassPropertiesNotEqualTo();

            yield from self::provClassPropertiesEqualButNotIdenticalTo();

            yield from self::provClassPropertiesNotEqualToNonClass();
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
            yield from self::provClassPropertiesIdenticalTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'ctorArgs' => [$case['expect']],
                'actual'   => $case['actual'],
                'message'  => self::message($case['string'], true),
            ];
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
