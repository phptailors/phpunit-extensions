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
 * @covers \Tailors\PHPUnit\Constraint\ObjectPropertiesEqualTo
 * @covers \Tailors\PHPUnit\Constraint\ObjectPropertiesEqualToTest
 * @covers \Tailors\PHPUnit\Properties\AbstractPropertiesConstraintTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type ConstraintClass = ObjectPropertiesEqualTo
 * @psalm-type CtorArgs        = list{ArrayLike}
 *
 * @template-extends AbstractPropertiesConstraintTestCase<ObjectPropertiesEqualTo, list{ArrayLike}>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class ObjectPropertiesEqualToTest extends AbstractPropertiesConstraintTestCase
{
    use ProvObjectPropertiesTrait;

    /**
     * @codeCoverageIgnore Called from providers only.
     */
    public static function complement(): string
    {
        return 'an object with properties equal to the specified ones';
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
        return ObjectPropertiesEqualTo::create(...$ctorArgs);
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
            yield from self::provObjectPropertiesIdenticalTo();

            yield from self::provObjectPropertiesEqualButNotIdenticalTo();
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
            yield from self::provObjectPropertiesNotEqualTo();

            yield from self::provObjectPropertiesNotEqualToNonObject();
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
            yield from self::provObjectPropertiesNotEqualTo();

            yield from self::provObjectPropertiesNotEqualToNonObject();
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
            yield from self::provObjectPropertiesIdenticalTo();

            yield from self::provObjectPropertiesEqualButNotIdenticalTo();
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
