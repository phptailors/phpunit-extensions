<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\RecursiveConstraint\RecursiveConstraintTestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template ConstraintClass of Constraint
 * @psalm-template CreateConstraintArgs of list
 *
 * @template-extends RecursiveConstraintTestCase<ConstraintClass>
 */
abstract class PropertiesConstraintTestCase extends RecursiveConstraintTestCase
{
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
     * @dataProvider provArrayWithNonStringKeys
     *
     * @psalm-param array $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testCreateWithNonStringKeys(array $array, int $count): void
    {
        $message = sprintf(
            'Argument 1 passed to %s::create() must be an associative array with string keys, '.
            'an array with %d non-string %s given',
            get_class(static::createConstraint([[]])),
            $count,
            $count > 1 ? 'keys' : 'key'
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        static::createConstraint([$array]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
