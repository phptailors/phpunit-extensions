<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use Tailors\PHPUnit\ArraySpec\DummyResultFactoryAndValueSelectorWrapper;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\IdentityComparator;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\ResultFactory\DummyArrayResultFactory;
use Tailors\PHPUnit\ValueSelector\DummyValueSelector;

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
     *
     * @psalm-param ArrayLike $expected
     */
    protected static function validateExpectations(iterable $expected, int $argument, int $distance = 1): void {}

    protected static function makeComparator(): ComparatorInterface
    {
        return new IdentityComparator();
    }

    /**
     * @psalm-param ArrayLike $expected
     *
     * @psalm-return DummyResultFactoryAndValueSelectorWrapper
     */
    protected static function makeExpectations(iterable $expected): iterable
    {
        return new DummyResultFactoryAndValueSelectorWrapper(
            new DummyArrayResultFactory(),
            new DummyValueSelector(
                function ($subject): bool {
                    return is_array($subject);
                },
                function ($subject, $key, &$retval) {
                    if (!array_key_exists($key, $subject)) {
                        return false;
                    }
                    $retval = $subject[$key];

                    return true;
                },
                'an array',
                'values'
            ),
            $expected
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
