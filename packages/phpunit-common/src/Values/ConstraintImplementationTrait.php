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
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
trait ConstraintImplementationTrait
{
    /**
     * @throws InvalidArgumentException
     */
    public static function create(array $expected): self
    {
        self::validateExpectations($expected, 1);

        $comparator = self::makeComparator();
        $selector = self::makeSelector();

        (new RecursiveComparatorValidator($comparator))->validate($expected, 1);

        return new self($comparator, new ExpectedValuesSelection($selector, $expected), new RecursiveUnwrapper());
    }

    /**
     * @throws InvalidArgumentException
     */
    abstract protected static function validateExpectations(array $expected, int $argument, int $distance = 1): void;

    /**
     * Creates instance of ValueSelectorInterface.
     */
    abstract protected static function makeSelector(): ValueSelectorInterface;

    /**
     * Creates instance of ComparatorInterface.
     */
    abstract protected static function makeComparator(): ComparatorInterface;
}

// vim: syntax=php sw=4 ts=4 et:
