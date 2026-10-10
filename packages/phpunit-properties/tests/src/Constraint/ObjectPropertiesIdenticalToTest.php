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

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\ObjectPropertiesIdenticalTo
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type ConstraintClass      = ObjectPropertiesIdenticalTo
 * @psalm-type CreateConstraintArgs = list{ArrayLike}
 *
 * @template-extends PropertiesConstraintTestCase<ObjectPropertiesIdenticalTo, list{ArrayLike}>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class ObjectPropertiesIdenticalToTest extends PropertiesConstraintTestCase
{
    use ProvObjectPropertiesTrait;

    public static function complement(): string
    {
        return 'an object with properties identical to the specified ones';
    }

    /**
     * @psalm-return class-string<ConstraintClass>
     *
     * @psalm-pure
     */
    public static function getConstraintClass(): string
    {
        return ObjectPropertiesIdenticalTo::class;
    }

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param CreateConstraintArgs $args
     *
     * @psalm-return ConstraintClass
     */
    public static function createConstraint(array $args): Constraint
    {
        return ObjectPropertiesIdenticalTo::create(...$args);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testObjectPropertiesIdenticalToSucceeds(array $expect, $actual): void
    {
        parent::examineValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     * @dataProvider provObjectPropertiesNotEqualToNonObject
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testObjectPropertiesIdenticalToFails(array $expect, $actual, string $string): void
    {
        parent::examineValuesMatchFails($expect, $actual, $string);
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     * @dataProvider provObjectPropertiesNotEqualToNonObject
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotObjectPropertiesIdenticalToSucceeds(array $expect, $actual): void
    {
        parent::examineNotValuesMatchSucceeds($expect, $actual);
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotObjectPropertiesIdenticalToFails(array $expect, $actual, string $string): void
    {
        parent::examineNotValuesMatchFails($expect, $actual, $string);
    }
}

// vim: syntax=php sw=4 ts=4 et:
