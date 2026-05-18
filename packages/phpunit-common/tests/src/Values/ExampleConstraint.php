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
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Recursive\AbstractRecursiveConstraint;
use Tailors\PHPUnit\Recursive\RecursiveConstraintSpecializationTrait;
use Tailors\PHPUnit\Selector\ArrayValueSelector;
use Tailors\PHPUnit\Selector\ValueSelectorInterface;

/**
 * Example constraint class that extends the AbstractConstraint.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExampleConstraint extends AbstractRecursiveConstraint
{
    use RecursiveConstraintSpecializationTrait;

    /**
     * @throws InvalidArgumentException
     */
    protected static function validateExpectations(array $expected, int $argument, int $distance = 1): void {}

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

    protected static function makeExpectedValues(array $array): ValuesInterface
    {
        return new ExpectedValues($array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
