<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\IdentityComparator;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\ConstraintImplementationTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class DummyConstraintImplementation
{
    use ConstraintImplementationTrait;

    public static ?ValueSelectorInterface $makeSelector;
    public static ?ComparatorInterface $makeComparator;
    public static ?array $validateExpectations;

    protected function __construct(public ValuesInterface $expected, public ComparatorInterface $comparator, public ValueSelectorInterface $valueSelector, public RecursiveUnwrapperInterface $unwrapper) {}

    protected static function validateExpectations(array $expected, int $argument, int $distance = 1): void
    {
        self::$validateExpectations = [$expected, $argument, $distance];
    }

    protected static function makeSelector(): ValueSelectorInterface
    {
        if (null === self::$makeSelector) {
            self::$makeSelector = new ArrayValueSelector();
        }

        return self::$makeSelector;
    }

    protected static function makeComparator(): ComparatorInterface
    {
        if (null === self::$makeComparator) {
            self::$makeComparator = new IdentityComparator();
        }

        return self::$makeComparator;
    }
}
// vim: syntax=php sw=4 ts=4 et:
