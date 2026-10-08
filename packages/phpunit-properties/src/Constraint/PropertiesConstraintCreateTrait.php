<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactory;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapper;

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
trait PropertiesConstraintCreateTrait
{
    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param ArrayLike $expectations
     */
    public static function create(iterable $expectations): self
    {
        $array = is_array($expectations) ? $expectations : iterator_to_array($expectations);
        self::assertStringKeysOnly($array, 1);

        return new self(
            self::makeExpectations($expectations),
            self::makeComparator(),
            RecursiveResultFactory::create(),
            RecursiveResultUnwrapper::create()
        );
    }

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

    /**
     * @psalm-assert array<string, mixed> $array
     *
     * @throws InvalidArgumentException
     *
     * @psalm-param array $array
     */
    private static function assertStringKeysOnly(array $array, int $argument, int $distance = 1): void
    {
        $valid = array_filter($array, 'is_string', ARRAY_FILTER_USE_KEY);
        if (($count = count($array) - count($valid)) > 0) {
            throw InvalidArgumentException::fromBackTrace(
                $argument,
                'an associative array with string keys',
                sprintf('an array with %d non-string %s', $count, $count > 1 ? 'keys' : 'key'),
                1 + $distance
            );
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
