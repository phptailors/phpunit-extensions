<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Arrays\AbstractKsortedConstraint;
use Tailors\PHPUnit\Arrays\ArrayKsorter;
use Tailors\PHPUnit\Arrays\ExpectedValuesSorting;
use Tailors\PHPUnit\Comparator\EqualityComparator;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Values\RecursiveComparatorValidator;
use Tailors\PHPUnit\Values\RecursiveUnwrapper;

/**
 * Constraint that accepts arrays equal to specified one when key-sorted.
 *
 * The ``==`` operator (equality) is used for comparison.
 *
 *
 *      $matcher = KsortedArrayEqualTo::create([
 *          'name' => 'John', 'age' => '21'
 *      ]);
 *
 *      self::assertThat([
 *          'age' => 21,
 *          'name' => 'John',
 *      ], $matcher);
 */
final class KsortedArrayEqualTo extends AbstractKsortedConstraint
{
    /**
     * @param array $expected
     *
     * @throws InvalidArgumentException
     */
    public static function create(array $expected, int $flags = SORT_REGULAR): self
    {
        $comparator = new EqualityComparator();

        (new RecursiveComparatorValidator($comparator))->validate($expected, 1);

        $sorting = new ExpectedValuesSorting(new ArrayKsorter($flags), $expected);

        return new self($comparator, $sorting, new RecursiveUnwrapper());
    }
}

// vim: syntax=php sw=4 ts=4 et:
