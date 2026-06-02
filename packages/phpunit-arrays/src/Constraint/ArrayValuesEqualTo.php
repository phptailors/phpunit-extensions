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
use Tailors\PHPUnit\Arrays\ArrayValuesSelection;
use Tailors\PHPUnit\Arrays\ExpectedArrayValues;
use Tailors\PHPUnit\Arrays\ValidateExpectationsTrait;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\EqualityComparator;
use Tailors\PHPUnit\RecursiveConstraint\AbstractRecursiveConstraint;
use Tailors\PHPUnit\RecursiveConstraint\RecursiveConstraintSpecializationTrait;
use Tailors\PHPUnit\ValueSelector\ArrayValueSelector;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;

/**
 * Constraint that accepts arrays having values equal to specified ones.
 *
 * Compares only values present in the array of expectations. The ``==``
 * operator (equality) is used for comparison.
 *
 *
 *      $matcher = ArrayValuesEqualTo::create([
 *          'name' => 'John', 'age' => '21'
 *      ]);
 *
 *      self::assertThat([
 *          'age' => 21,
 *          'name' => 'John',
 *      ], $matcher);
 */
final class ArrayValuesEqualTo extends AbstractRecursiveConstraint
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
     *
     * @psalm-return ArrayLike
     */
    protected static function makeExpectations(iterable $expected): ArrayValuesSelection
    {
        return new ArrayValuesSelection($expected);
    }
}

// vim: syntax=php sw=4 ts=4 et:
