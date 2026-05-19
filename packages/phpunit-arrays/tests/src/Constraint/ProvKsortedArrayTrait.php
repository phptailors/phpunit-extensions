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

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
trait ProvKsortedArrayTrait
{
    // @codeCoverageIgnoreStart
    abstract public static function createConstraint(mixed ...$args): Constraint;

    public static function provKsortedArrayIdenticalTo(): iterable
    {
        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => [],
            'actual' => [],
            'string' => 'array',
        ];

        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['a' => 'A', 'b' => 'B'],
            'actual' => ['a' => 'A', 'b' => 'B'],
            'string' => 'array',
        ];

        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['b' => 'B', 'a' => 'A'],
            'actual' => ['a' => 'A', 'b' => 'B'],
            'string' => 'array',
        ];

        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['a' => 'A', 'b' => 'B'],
            'actual' => ['b' => 'B', 'a' => 'A'],
            'string' => 'array',
        ];

        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['a' => false, 'b' => 0, 'c' => '', 'd' => null],
            'actual' => ['a' => false, 'b' => 0, 'c' => '', 'd' => null],
            'string' => 'array',
        ];

        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['a' => ['b' => 'B']],
            'actual' => ['a' => ['b' => 'B']],
            'string' => 'array',
        ];
    }

    public static function provKsortedArrayEqualButNotIdenticalTo(): iterable
    {
        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => [
                'int321'    => '321',
                'empty'     => null,
                'null'      => '',
                'string123' => 123,
                'boolFalse' => 0,
            ],
            'actual' => [
                'empty'     => '',
                'null'      => null,
                'int321'    => 321,
                'boolFalse' => false,
                'string123' => '123',
            ],
            'string' => 'array',
        ];

        // Nested arrays are not sorted
        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'BAR', 'gez' => 'GEZ']],
            'actual' => ['foo' => 'FOO', 'arr' => ['gez' => 'GEZ', 'bar' => 'BAR']],
            'string' => 'array',
        ];
    }

    public static function provKsortedArrayNotEqualTo(): iterable
    {
        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'bar' => 'GEZ', 'int' => 21],
            'actual' => ['foo' => 'FOO', 'bar' => 'BAR', 'int' => 21],
            'string' => 'array',
        ];

        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'GEZ']],
            'actual' => ['foo' => 'FOO', 'arr' => ['bar' => 'BAR']],
            'string' => 'array',
        ];

        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'GEZ']],
            'actual' => 'arbitrary string',
            'string' => '\'arbitrary string\'',
        ];

        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'GEZ']],
            'actual' => \Exception::class,
            'string' => \Exception::class,
        ];
    }

    public static function provKsortedArrayNotEqualToNonArray(): iterable
    {
        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => 123,
            'string' => '123',
        ];

        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => 'arbitrary string',
            'string' => '\'arbitrary string\'',
        ];

        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => null,
            'string' => 'null',
        ];

        yield 'ProvKsortedArrayTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => new \stdClass(),
            'string' => 'object stdClass',
        ];
    }

    // @codeCoverageIgnoreEnd
}
