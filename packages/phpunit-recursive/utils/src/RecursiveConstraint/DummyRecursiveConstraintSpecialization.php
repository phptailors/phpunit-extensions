<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use Tailors\PHPUnit\ArraySpec\DummyResultFactoryWrapper;
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
 * @psalm-import-type ArrayLike from \Tailors\PHPUnit\Common\TypesInterface
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
     * @var RecursiveResultUnwrapperInterface
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
    public static $validateExpectationsLastParams;

    /**
     * @var ?array
     */
    public static $makeComparatorLastParams;

    /**
     * @var ?ComparatorInterface
     */
    public static $makeComparatorLastReturn;

    /**
     * @var ?array
     */
    public static $makeExpectationsLastParams;

    /**
     * @var ?iterable
     *
     * @psalm-var ?ArrayLike
     */
    public static $makeExpectationsLastReturn;

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

    public static function resetStaticProperties(): void
    {
        self::$defaultComparatorCompare = false;
        self::$defaultComparatorAdjective = 'dummy';
        self::$defaultResultFactory = null;
        self::$validateExpectationsLastParams = null;
        self::$makeComparatorLastParams = null;
        self::$makeComparatorLastReturn = null;
        self::$makeExpectationsLastParams = null;
        self::$makeExpectationsLastReturn = null;
    }

    /**
     * @psalm-param ArrayLike $expected
     */
    protected static function validateExpectations(iterable $expected, int $argument, int $distance = 1): void
    {
        self::$validateExpectationsLastParams = [$expected, $argument, $distance];
    }

    protected static function makeComparator(): ComparatorInterface
    {
        self::$makeComparatorLastParams = [];
        self::$makeComparatorLastReturn = new DummyComparator(self::$defaultComparatorCompare, self::$defaultComparatorAdjective);

        return self::$makeComparatorLastReturn;
    }

    /**
     * @psalm-param ArrayLike $expected
     *
     * @psalm-return ArrayLike
     */
    protected static function makeExpectations(iterable $expected): iterable
    {
        self::$makeExpectationsLastParams = [$expected];
        self::$makeExpectationsLastReturn = new DummyResultFactoryWrapper(self::defaultResultFactory(), $expected);

        return self::$makeExpectationsLastReturn;
    }

    private static function defaultResultFactory(): ResultFactoryInterface
    {
        if (null === self::$defaultResultFactory) {
            self::$defaultResultFactory = new DummyArrayResultFactory();
        }

        return self::$defaultResultFactory;
    }
}
// vim: syntax=php sw=4 ts=4 et:
