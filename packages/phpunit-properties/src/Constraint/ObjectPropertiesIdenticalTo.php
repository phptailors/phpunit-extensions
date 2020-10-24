<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\IdentityComparator;
use Tailors\PHPUnit\Properties\ValidateExpectationsTrait;
use Tailors\PHPUnit\Values\AbstractConstraint;
use Tailors\PHPUnit\Values\ConstraintImplementationTrait;
use Tailors\PHPUnit\Values\ObjectPropertySelector;
use Tailors\PHPUnit\Values\ValueSelectorInterface;

/**
 * Constraint that accepts objects having properties identical to specified ones.
 *
 * Compares only properties present in the array of expectations. A property is
 * defined as either an attribute value or a value returned by object's method
 * callable without arguments. The ``===`` operator (identity) is used for
 * comparison.
 *
 *
 * Any key in *$expected* array ending with ``"()"`` is considered to be a
 * method that returns property value.
 *
 *      // ...
 *      $matcher = ObjectPropertiesIdenticalTo::create([
 *          'getName()' => 'John', 'age' => 21
 *      ]);
 *
 *      self::assertThat(new class {
 *          public static $age = 21;
 *          public static getName(): string {
 *              return 'John';
 *          }
 *      }, $matcher);
 */
final class ObjectPropertiesIdenticalTo extends AbstractConstraint
{
    use ConstraintImplementationTrait;
    use ValidateExpectationsTrait;

    /**
     * Creates instance of IdentityComparator.
     */
    protected static function makeComparator(): ComparatorInterface
    {
        return new IdentityComparator();
    }

    /**
     * Creates instance of ObjectPropertySelector.
     */
    protected static function makeSelector(): ValueSelectorInterface
    {
        return new ObjectPropertySelector();
    }
}

// vim: syntax=php sw=4 ts=4 et:
