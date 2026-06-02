<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use Tailors\PHPUnit\ArrayResult\ArrayResultInterface;
use Tailors\PHPUnit\ArrayResult\DummyArrayResult;
use Tailors\PHPUnit\ArraySpec\DummyArraySpec;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\IdentityComparator;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactoryInterface;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapperInterface;
use Tailors\PHPUnit\ValueSelector\ArrayValueSelector;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;

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
     * @var null|ComparatorInterface
     */
    public static $makeComparator;

    /**
     * @var null|array
     */
    public static $validateExpectations;

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
        self::$validateExpectations = [$expected, $argument, $distance];
    }

    protected static function makeComparator(): ComparatorInterface
    {
        if (null === self::$makeComparator) {
            self::$makeComparator = new IdentityComparator();
        }

        return self::$makeComparator;
    }

    /**
     * @psalm-param ArrayLike $expected
     */
    protected static function makeExpectations(iterable $expected): DummyArraySpec
    {
        if (null === self::$makeExpectations) {
            new DummyArraySpec($resultFactory, $expected);
        }
    }
}
// vim: syntax=php sw=4 ts=4 et:
