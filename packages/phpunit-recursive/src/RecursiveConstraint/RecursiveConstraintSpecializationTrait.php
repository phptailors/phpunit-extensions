<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactory;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapper;

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 *
 * @psalm-require-extends AbstractRecursiveConstraint
 */
trait RecursiveConstraintSpecializationTrait
{
    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param ArrayLike $expected
     */
    public static function create(iterable $expected): self
    {
        self::validateExpectations($expected, 1);

        $expected = self::makeExpectations($expected);
        $comparator = self::makeComparator();

        $recursiveResultFactory = RecursiveResultFactory::create();
        $recursiveResultUnwrapper = RecursiveResultUnwrapper::create();

        return new self($expected, $comparator, $recursiveResultFactory, $recursiveResultUnwrapper);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param ArrayLike $expected
     */
    abstract protected static function validateExpectations(iterable $expected, int $argument, int $distance = 1): void;

    /**
     * Creates instance of ComparatorInterface.
     */
    abstract protected static function makeComparator(): ComparatorInterface;

    /**
     * Creates expectations.
     *
     * @psalm-param ArrayLike $expected
     *
     * @psalm-return ArrayLike
     */
    abstract protected static function makeExpectations(iterable $expected): iterable;
}

// vim: syntax=php sw=4 ts=4 et:
