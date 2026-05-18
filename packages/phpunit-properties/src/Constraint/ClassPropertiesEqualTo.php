<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\EqualityComparator;
use Tailors\PHPUnit\Properties\ExpectedClassProperties;
use Tailors\PHPUnit\Properties\ValidateExpectationsTrait;
use Tailors\PHPUnit\Recursive\AbstractRecursiveConstraint;
use Tailors\PHPUnit\Recursive\RecursiveConstraintSpecializationTrait;
use Tailors\PHPUnit\Selector\ClassPropertySelector;
use Tailors\PHPUnit\Selector\ValueSelectorInterface;
use Tailors\PHPUnit\Values\ValuesInterface;

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
    #[\Override]
    protected static function makeComparator(): ComparatorInterface
    {
        return new EqualityComparator();
    }

    /**
     * Creates instance of ClassPropertySelector.
     */
    #[\Override]
    protected static function makeSelector(): ValueSelectorInterface
    {
        return new ClassPropertySelector();
    }

    /**
     * Creates instance of ValuesInterface to be used as expected values.
     */
    protected static function makeExpectedValues(array $array): ValuesInterface
    {
        return new ExpectedClassProperties($array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
