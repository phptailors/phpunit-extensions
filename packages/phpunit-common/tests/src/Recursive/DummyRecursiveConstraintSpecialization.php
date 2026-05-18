<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Recursive;

use PHPUnit\Framework\Attributes\Small;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\IdentityComparator;
use Tailors\PHPUnit\Selector\ArrayValueSelector;
use Tailors\PHPUnit\Selector\ValueSelectorInterface;
use Tailors\PHPUnit\Values\DummyValues;
use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * @covers \Tailors\PHPUnit\Values\ConstraintImplementationTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[Small]
final class DummyRecursiveConstraintSpecialization
{
    use RecursiveConstraintSpecializationTrait;

    public static ?ValueSelectorInterface $makeSelector;
    public static ?ComparatorInterface $makeComparator;
    public static ?array $validateExpectations;

    protected function __construct(public ValuesInterface $expected, public ComparatorInterface $comparator, public ValueSelectorInterface $valueSelector, public RecursiveUnwrapperInterface $unwrapper) {}

    #[\Override]
    protected static function validateExpectations(array $expected, int $argument, int $distance = 1): void
    {
        self::$validateExpectations = [$expected, $argument, $distance];
    }

    #[\Override]
    protected static function makeSelector(): ValueSelectorInterface
    {
        if (null === self::$makeSelector) {
            self::$makeSelector = new ArrayValueSelector();
        }

        return self::$makeSelector;
    }

    #[\Override]
    protected static function makeComparator(): ComparatorInterface
    {
        if (null === self::$makeComparator) {
            self::$makeComparator = new IdentityComparator();
        }

        return self::$makeComparator;
    }

    protected static function makeExpectedValues(array $array): ValuesInterface
    {
        return new DummyValues(false, $array);
    }
}
// vim: syntax=php sw=4 ts=4 et:
