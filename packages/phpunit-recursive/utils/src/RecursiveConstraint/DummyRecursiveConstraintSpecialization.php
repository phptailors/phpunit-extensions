<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use Tailors\PHPUnit\ArraySpec\DummyArraySpec;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\DummyComparator;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactoryInterface;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapperInterface;
use Tailors\PHPUnit\ResultFactory\DummyArrayResultFactory;
use Tailors\PHPUnit\ResultFactory\ResultFactoryInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveConstraint\RecursiveConstraintSpecializationTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
final class DummyRecursiveConstraintSpecialization
{
    use RecursiveConstraintSpecializationTrait;

    /**
     * @var ArrayLike
     */
    public $expected;

    /**
     * @var ComparatorInterface
     */
    public $comparator;

    /**
     * @var RecursiveResultFactoryInterface
     */
    public $recursiveResultFactory;

    /**
     * @var RecursiveUnwrapperInterface
     */
    public $recursiveResultUnwrapper;

    /**
     * @var bool
     */
    public static $defaultComparatorCompare = false;

    /**
     * @var string
     */
    public static $defaultComparatorAdjective = 'dummy';

    /**
     * @var ?ResultFactoryInterface
     */
    public static $defaultResultFactory;

    /**
     * @var ?array
     */
    public static $validateExpectationsLastCall;

    /**
     * @var ?array
     */
    public static $makeComparatorLastCall;

    /**
     * @var ?ComparatorInterface
     */
    public static $makeComparatorLastResult;

    /**
     * @var ?array
     */
    public static $makeExpectationsLastCall;

    /**
     * @var ?iterable
     *
     * @psalm-var ?ArrayLike
     */
    public static $makeExpectationsLastResult;

    /**
     * @psalm-param ArrayLike $expected
     */
    protected function __construct(
        iterable $expected,
        ComparatorInterface $comparator,
        RecursiveResultFactoryInterface $recursiveResultFactory,
        RecursiveResultUnwrapperInterface $recursiveResultUnwrapper
    ) {
        $this->expected = $expected;
        $this->comparator = $comparator;
        $this->recursiveResultFactory = $recursiveResultFactory;
        $this->recursiveResultUnwrapper = $recursiveResultUnwrapper;
    }

    /**
     * @psalm-param ArrayLike $expected
     */
    protected static function validateExpectations(iterable $expected, int $argument, int $distance = 1): void
    {
        self::$validateExpectationsLastCall = [$expected, $argument, $distance];
    }

    protected static function makeComparator(): ComparatorInterface
    {
        self::$makeComparatorLastCall = [];
        self::$makeComparatorLastResult = new DummyComparator(self::$defaultComparatorCompare, self::$defaultComparatorAdjective);

        return self::$makeComparatorLastResult;
    }

    /**
     * @psalm-param ArrayLike $expected
     */
    protected static function makeExpectations(iterable $expected): DummyArraySpec
    {
        self::$makeExpectationsLastCall = [$expected];
        self::$makeExpectationsLastResult = new DummyArraySpec(self::defaultResultFactory(), $expected);

        return self::$makeExpectationsLastResult;
    }

    private static function defaultResultFactory(): ResultFactoryInterface
    {
        if (null === self::$defaultResultFactory) {
            self::$defaultResultFactory = new DummyArrayResultFactory();
        }
        return self::$defaultResultFactory;
    }

    public static function staticReset(): void
    {
        self::$defaultComparatorCompare = false;
        self::$defaultComparatorAdjective = 'dummy';
        self::$defaultResultFactory = null;
        self::$validateExpectationsLastCall = null;
        self::$makeComparatorLastCall = null;
        self::$makeComparatorLastResult = null;
        self::$makeExpectationsLastCall = null;
        self::$makeExpectationsLastResult = null;
    }
}
// vim: syntax=php sw=4 ts=4 et:
