<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Arrays\ValidateExpectationsTrait;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\IdentityComparator;
use Tailors\PHPUnit\Values\AbstractConstraint;
use Tailors\PHPUnit\Values\ArrayValueSelector;
use Tailors\PHPUnit\Values\ConstraintImplementationTrait;
use Tailors\PHPUnit\Values\ValueSelectorInterface;

/**
 * Constraint that accepts arrays having values equal to specified ones.
 *
 * Compares only values present in the array of expectations. The ``===``
 * operator (identity) is used for comparison.
 *
 *
 *      $matcher = ArrayValuesIdenticalTo::create([
 *          'name' => 'John', 'age' => '21'
 *      ]);
 *
 *      self::assertThat([
 *          'age' => '21',
 *          'name' => 'John',
 *      ], $matcher);
 */
final class ArrayValuesIdenticalTo extends AbstractConstraint
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
     * Creates instance of ArrayValueSelector.
     */
    protected static function makeSelector(): ValueSelectorInterface
    {
        return new ArrayValueSelector();
    }
}

// vim: syntax=php sw=4 ts=4 et:
