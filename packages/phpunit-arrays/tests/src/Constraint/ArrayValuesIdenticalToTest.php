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
use Tailors\PHPUnit\RecursiveConstraint\RecursiveConstraintTestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\ArrayValuesIdenticalTo
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type ConstraintClass      = ArrayValuesIdenticalTo
 * @psalm-type CreateConstraintArgs = list{ArrayLike}
 *
 * @template-extends RecursiveConstraintTestCase<ArrayValuesIdenticalTo>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class ArrayValuesIdenticalToTest extends RecursiveConstraintTestCase
{
    use ProvArrayValuesTrait;

    public static function complement(): string
    {
        return 'an array or ArrayAccess with values identical to the specified ones';
    }

    /**
     * @psalm-return class-string<ArrayValuesIdenticalTo>
     *
     * @psalm-pure
     */
    public static function getConstraintClass(): string
    {
        return ArrayValuesIdenticalTo::class;
    }

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param CreateConstraintArgs $args
     */
    public static function createConstraint(array $args): Constraint
    {
        return ArrayValuesIdenticalTo::create(...$args);
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testArrayValuesIdenticalToSucceeds(array $expect, $actual): void
    {
        parent::examineValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     * @dataProvider provArrayValuesNotEqualToNonArray
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testArrayValuesIdenticalToFails(array $expect, $actual, string $string): void
    {
        parent::examineValuesMatchFails($expect, $actual, $string);
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     *
     * @dateProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @dataProvider provArrayValuesNotEqualToNonArray
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotArrayValuesIdenticalToSucceeds(array $expect, $actual): void
    {
        parent::examineNotValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotArrayValuesIdenticalToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
