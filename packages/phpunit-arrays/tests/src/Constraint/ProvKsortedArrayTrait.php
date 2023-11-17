<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
trait ProvKsortedArrayTrait
{
    // @codeCoverageIgnoreStart

    public static function provKsortedArrayIdenticalTo(): array
    {
        return [
            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => [],
                'actual' => [],
                'string' => 'array',
            ],

            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['a' => 'A', 'b' => 'B'],
                'actual' => ['a' => 'A', 'b' => 'B'],
                'string' => 'array',
            ],

            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['b' => 'B', 'a' => 'A'],
                'actual' => ['a' => 'A', 'b' => 'B'],
                'string' => 'array',
            ],

            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['a' => 'A', 'b' => 'B'],
                'actual' => ['b' => 'B', 'a' => 'A'],
                'string' => 'array',
            ],

            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['a' => false, 'b' => 0, 'c' => '', 'd' => null],
                'actual' => ['a' => false, 'b' => 0, 'c' => '', 'd' => null],
                'string' => 'array',
            ],

            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['a' => ['b' => 'B']],
                'actual' => ['a' => ['b' => 'B']],
                'string' => 'array',
            ],
        ];
    }

    public static function provKsortedArrayEqualButNotIdenticalTo(): array
    {
        return [
            'ProvKsortedArrayTrait.php:'.__LINE__ => [
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
            ],

            // Nested arrays are not sorted
            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'BAR', 'gez' => 'GEZ']],
                'actual' => ['foo' => 'FOO', 'arr' => ['gez' => 'GEZ', 'bar' => 'BAR']],
                'string' => 'array',
            ],
        ];
    }

    public static function provKsortedArrayNotEqualTo(): array
    {
        return [
            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO', 'bar' => 'GEZ', 'int' => 21],
                'actual' => ['foo' => 'FOO', 'bar' => 'BAR', 'int' => 21],
                'string' => 'array',
            ],

            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'GEZ']],
                'actual' => ['foo' => 'FOO', 'arr' => ['bar' => 'BAR']],
                'string' => 'array',
            ],

            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'GEZ']],
                'actual' => 'arbitrary string',
                'string' => '\'arbitrary string\'',
            ],

            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'GEZ']],
                'actual' => \Exception::class,
                'string' => \Exception::class,
            ],
        ];
    }

    public static function provKsortedArrayNotEqualToNonArray(): array
    {
        return [
            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => 123,
                'string' => '123',
            ],

            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => 'arbitrary string',
                'string' => '\'arbitrary string\'',
            ],

            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => null,
                'string' => 'null',
            ],

            'ProvKsortedArrayTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => new \stdClass(),
                'string' => 'object stdClass',
            ],
        ];
    }

    // @codeCoverageIgnoreEnd
}
