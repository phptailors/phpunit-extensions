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
use Tailors\PHPUnit\Comparator\EqualityComparator;

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
    public static function create(array $expected, int $flags = SORT_REGULAR): self
    {
        return new self(new EqualityComparator(), $expected, $flags);
    }
}

// vim: syntax=php sw=4 ts=4 et:
