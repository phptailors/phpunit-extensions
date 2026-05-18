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
use Tailors\PHPUnit\Recursive\RecursiveUnwrapperInterface;
use Tailors\PHPUnit\Selector\ArrayValueSelector;
use Tailors\PHPUnit\Selector\ValueSelectorInterface;

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

    /**
     * @var ValuesInterface
     */
    public $expected;

    /**
     * @var ComparatorInterface
     */
    public $comparator;

    /**
     * @var ValueSelectorInterface
     */
    public $valueSelector;

    /**
     * @var RecursiveUnwrapperInterface
     */
    public $unwrapper;

    /**
     * @var null|ValueSelectorInterface
     */
    public static $makeSelector;

    /**
     * @var null|ComparatorInterface
     */
    public static $makeComparator;

    /**
     * @var null|array
     */
    public static $validateExpectations;

    protected function __construct(
        ValuesInterface $expected,
        ComparatorInterface $comparator,
        ValueSelectorInterface $valueSelector,
        RecursiveUnwrapperInterface $unwrapper
    ) {
        $this->expected = $expected;
        $this->comparator = $comparator;
        $this->valueSelector = $valueSelector;
        $this->unwrapper = $unwrapper;
    }

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

    protected static function makeExpectedValues(array $array): ValuesInterface
    {
        return new DummyValues(false, $array);
    }
}
// vim: syntax=php sw=4 ts=4 et:
