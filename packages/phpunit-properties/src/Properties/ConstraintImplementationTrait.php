<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @internal This trait is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
trait ConstraintImplementationTrait
{
    /**
     * @throws InvalidArgumentException
     */
    public static function create(array $expected): self
    {
        self::assertStringKeysOnly($expected, 1);

        $comparator = self::makeComparator();
        $selector = self::makePropertySelector();

        return new self($comparator, new ExpectedProperties($selector, $expected), new RecursivePropertiesUnwrapper());
    }

    /**
     * Creates instance of ClassPropertySelector.
     */
    abstract protected static function makePropertySelector(): PropertySelectorInterface;

    /**
     * Creates instance of EqualityComparator.
     */
    abstract protected static function makeComparator(): ComparatorInterface;

    /**
     * @psalm-assert array<string, mixed> $array
     *
     * @throws InvalidArgumentException
     */
    private static function assertStringKeysOnly(array $array, int $argument, int $depth = 1): void
    {
        $valid = array_filter($array, 'is_string', ARRAY_FILTER_USE_KEY);
        if (($count = count($array) - count($valid)) > 0) {
            throw InvalidArgumentException::fromBackTrace(
                $argument,
                'an associative array with string keys',
                sprintf('an array with %d non-string %s', $count, $count > 1 ? 'keys' : 'key'),
                1 + $depth
            );
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
