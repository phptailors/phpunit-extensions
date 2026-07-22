<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use Tailors\PHPUnit\ArrayResult\ExpectedArrayResult;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\IdentityComparator;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * Example constraint class that extends the AbstractConstraint.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
class ExampleRecursiveConstraint extends AbstractRecursiveConstraint
{
    use RecursiveConstraintSpecializationTrait;

    /**
     * @throws InvalidArgumentException
     */
    protected static function validateExpectations(array $expected, int $argument, int $distance = 1): void {}

    /**
     * Creates instance of ComparatorInterface.
     */
    protected static function makeComparator(): ComparatorInterface
    {
        return new IdentityComparator();
    }

    /**
     * @psalm-param ArrayLike $expected
     *
     * @psalm-return ExpectedArrayResult
     */
    protected static function makeExpectations(iterable $expected): iterable
    {
        return new ExpectedArrayResult($expected);
    }
}

// vim: syntax=php sw=4 ts=4 et:
