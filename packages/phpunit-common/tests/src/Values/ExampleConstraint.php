<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\IdentityComparator;

/**
 * Example constraint class that extends the AbstractConstraint.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
class ExampleConstraint extends AbstractConstraint
{
    use ConstraintImplementationTrait;

    /**
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    protected static function validateExpectations(array $expected, int $argument, int $distance = 1): void
    {
    }

    /**
     * Creates instance of ValueSelectorInterface.
     */
    protected static function makeSelector(): ValueSelectorInterface
    {
        return new ArrayValueSelector();
    }

    /**
     * Creates instance of ComparatorInterface.
     */
    protected static function makeComparator(): ComparatorInterface
    {
        return new IdentityComparator();
    }
}

// vim: syntax=php sw=4 ts=4 et:
