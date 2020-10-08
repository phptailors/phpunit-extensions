<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\Comparator\ComparatorInterface;

/**
 * @internal This trait is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
trait ConstraintImplementationTrait
{
    /**
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function create(array $expected): self
    {
        self::validateExpectations($expected, 1);

        $comparator = self::makeComparator();
        $selector = self::makeSelector();

        return new self($comparator, new Selection($selector, $expected), new RecursiveUnwrapper());
    }

    /**
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    abstract protected static function validateExpectations(array $expected, int $argument, int $depth = 1): void;

    /**
     * Creates instance of ValueSelectorInterface.
     */
    abstract protected static function makeSelector(): ValueSelectorInterface;

    /**
     * Creates instance of EqualityComparator.
     */
    abstract protected static function makeComparator(): ComparatorInterface;
}

// vim: syntax=php sw=4 ts=4 et:
