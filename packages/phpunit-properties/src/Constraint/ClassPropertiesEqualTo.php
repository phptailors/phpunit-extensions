<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Properties\AbstractConstraint;
use Tailors\PHPUnit\Properties\ClassPropertySelector;
use Tailors\PHPUnit\Properties\ComparatorInterface;
use Tailors\PHPUnit\Properties\ConstraintImplementationTrait;
use Tailors\PHPUnit\Properties\EqualityComparator;
use Tailors\PHPUnit\Properties\PropertySelectorInterface;

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
final class ClassPropertiesEqualTo extends AbstractConstraint
{
    use ConstraintImplementationTrait;

    /**
     * Creates instance of EqualityComparator.
     */
    protected static function makeComparator(): ComparatorInterface
    {
        return new EqualityComparator();
    }

    /**
     * Creates instance of ClassPropertySelector.
     */
    protected static function makePropertySelector(): PropertySelectorInterface
    {
        return new ClassPropertySelector();
    }
}

// vim: syntax=php sw=4 ts=4 et:
