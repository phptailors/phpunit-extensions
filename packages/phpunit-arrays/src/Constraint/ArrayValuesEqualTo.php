<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Arrays\ExpectedArrayValues;
use Tailors\PHPUnit\Arrays\ValidateExpectationsTrait;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\EqualityComparator;
use Tailors\PHPUnit\Selector\ArrayValueSelector;
use Tailors\PHPUnit\Selector\ValueSelectorInterface;
use Tailors\PHPUnit\Values\AbstractConstraint;
use Tailors\PHPUnit\Values\ConstraintImplementationTrait;
use Tailors\PHPUnit\Values\ValuesInterface;

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
final class ArrayValuesEqualTo extends AbstractConstraint
{
    use ConstraintImplementationTrait;
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
     * Creates instance of ArrayValueSelector.
     */
    #[\Override]
    protected static function makeSelector(): ValueSelectorInterface
    {
        return new ArrayValueSelector();
    }

    /**
     * Creates instance of ValuesInterface to be used as expected values.
     */
    protected static function makeExpectedValues(array $array): ValuesInterface
    {
        return new ExpectedArrayValues($array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
