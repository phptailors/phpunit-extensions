<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Polyfill\ExpectExceptionMessageIsOrContainsTrait;
use Tailors\PHPUnit\RecursiveConstraint\AbstractRecursiveConstraintTestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template ConstraintClass of Constraint
 * @psalm-template CtorArgs of list
 *
 * @template-extends AbstractRecursiveConstraintTestCase<ConstraintClass>
 */
abstract class AbstractPropertiesConstraintTestCase extends AbstractRecursiveConstraintTestCase
{
    use ExpectExceptionMessageIsOrContainsTrait;

    /**
     * @psalm-return iterable<string, array{
     *      array: array,
     *      count: int
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provArrayWithNonStringKeys(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'array' => [
                'a' => 'A',
                0   => 'B',
            ],
            'count' => 1,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array' => [
                'a' => 'A',
                0   => 'B',
                2   => 'C',
                7   => 'D',
                'e' => 'E',
            ],
            'count' => 3,
        ];
    }

    /**
     * @psalm-param array $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provArrayWithNonStringKeys')]
    final public function testCreateWithNonStringKeys(array $array, int $count): void
    {
        $message = sprintf(
            'Argument 1 passed to %s::create() must be an associative array with string keys, '.
            'an array with %d non-string %s given',
            static::createConstraint([[]])::class,
            $count,
            $count > 1 ? 'keys' : 'key'
        );

        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessageIsOrContains($message);

        static::createConstraint([$array]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
