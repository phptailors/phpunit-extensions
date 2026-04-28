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
trait ProvArrayValuesTrait
{
    // @codeCoverageIgnoreStart

    /**
     * @param mixed $args
     */
    abstract public static function createConstraint(...$args): Constraint;

    public static function provArrayValuesIdenticalTo(): array
    {
        return [
            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => [],
                'actual' => [],
                'string' => 'array',
            ],

            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => [],
                'actual' => ['foo' => 'FOO', 'bar' => 'BAR'],
                'string' => 'array',
            ],

            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => ['foo' => 'FOO'],
                'string' => 'array',
            ],

            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => ['foo' => 'FOO', 'bar' => 'BAR'],
                'string' => 'array',
            ],

            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => new \ArrayObject(['foo' => 'FOO']),
                'string' => 'object ArrayObject',
            ],

            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => new \ArrayObject(['foo' => 'FOO', 'bar' => 'BAR']),
                'string' => 'object ArrayObject',
            ],
        ];
    }

    public static function provArrayValuesEqualButNotIdenticalTo(): array
    {
        return [
            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => [
                    'emptyString' => null,
                    'null'        => '',
                    'string123'   => 123,
                    'int321'      => '321',
                    'boolFalse'   => 0,
                ],
                'actual' => [
                    'emptyString' => '',
                    'null'        => null,
                    'string123'   => '123',
                    'int321'      => 321,
                    'boolFalse'   => false,
                ],
                'string' => 'array',
            ],

            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO', 'arr' => new \ArrayObject(['bar' => 'BAR'])],
                'actual' => ['foo' => 'FOO', 'arr' => new \ArrayObject(['bar' => 'BAR'])],
                'string' => 'array',
            ],
        ];
    }

    public static function provArrayValuesNotEqualTo(): array
    {
        return [
            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO', 'bar' => 'GEZ', 'int' => 21],
                'actual' => ['foo' => 'FOO', 'bar' => 'BAR', 'int' => 21],
                'string' => 'array',
            ],

            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'GEZ']],
                'actual' => ['foo' => 'FOO', 'arr' => ['bar' => 'BAR']],
                'string' => 'array',
            ],

            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'BAR']],
                'actual' => ['foo' => 'FOO', 'arr' => new \ArrayObject(['bar' => 'BAR'])],
                'string' => 'array',
            ],

            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO', 'arr' => new \ArrayObject(['bar' => 'BAR'])],
                'actual' => ['foo' => 'FOO', 'arr' => ['bar' => 'BAR']],
                'string' => 'array',
            ],
        ];
    }

    public static function provArrayValuesNotEqualToNonArray(): array
    {
        return [
            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => 123,
                'string' => '123',
            ],

            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => 'arbitrary string',
                'string' => '\'arbitrary string\'',
            ],

            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => null,
                'string' => 'null',
            ],

            'ProvArrayValuesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => new \stdClass(),
                'string' => 'object stdClass',
            ],
        ];
    }

    // @codeCoverageIgnoreEnd
}
