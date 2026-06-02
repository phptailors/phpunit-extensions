<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\ArrayResult\ArrayResultInterface;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\EqualityComparator;
use Tailors\PHPUnit\Properties\ClassPropertiesSelection;
use Tailors\PHPUnit\Properties\ExpectedClassProperties;
use Tailors\PHPUnit\Properties\ValidateExpectationsTrait;
use Tailors\PHPUnit\RecursiveConstraint\AbstractRecursiveConstraint;
use Tailors\PHPUnit\RecursiveConstraint\RecursiveConstraintSpecializationTrait;
use Tailors\PHPUnit\ValueSelector\ClassPropertySelector;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;

/**
 * Constraint that accepts classes having properties equal to specified ones.
 *
 * Compares only properties present in the array of expectations. A property is
 * defined as either a static attribute value or a value returned by class'
 * static method callable without arguments. The ``==`` operator (equality) is
 * used for comparison.
 *
 *
 * Any key in *$expected* array ending with ``"()"`` is considered to be a
 * method that returns property value.
 *
 *      // ...
 *      $matcher = ClassPropertiesEqualTo::create([
 *          'getName()' => 'John', 'age' => '21'
 *      ]);
 *
 *      self::assertThat(get_class(new class {
 *          public static $age = 21;
 *          public static getName(): string {
 *              return 'John';
 *          }
 *      }), $matcher);
 */
final class ClassPropertiesEqualTo extends AbstractRecursiveConstraint
{
    use RecursiveConstraintSpecializationTrait;
    use ValidateExpectationsTrait;

    /**
     * Creates instance of EqualityComparator.
     */
    protected static function makeComparator(): ComparatorInterface
    {
        return new EqualityComparator();
    }

    /**
     * Creates instance of ValuesInterface to be used as expected values.
     *
     * @psalm-param ArrayLike $expected
     */
    protected static function makeExpectations(iterable $expected): ClassPropertiesSelection
    {
        return new ClassPropertiesSelection($expected);
    }
}

// vim: syntax=php sw=4 ts=4 et:
