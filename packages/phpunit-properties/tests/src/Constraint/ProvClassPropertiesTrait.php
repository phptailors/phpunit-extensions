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

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type CreateConstraintArgs = list{ArrayLike}
 */
trait ProvClassPropertiesTrait
{
    // @codeCoverageIgnoreStart

    /**
     * @psalm-return iterable<array-key, array{
     *      expect: array<string, mixed>,
     *      actual: string,
     *      string: string
     * }>
     */
    public static function provClassPropertiesIdenticalTo(): iterable
    {
        $classes = [
            get_class(new class() {
                /** @var string */
                public static $emptyString = '';

                /** @var mixed */
                public static $null;

                /** @var string */
                public static $string123 = '123';

                /** @var int */
                public static $int321 = 321;

                /** @var bool */
                public static $boolFalse = false;
            }),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [],
            'actual' => $classes[0],
            'string' => $classes[0],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'emptyString' => '',
                'null'        => null,
                'string123'   => '123',
                'int321'      => 321,
                'boolFalse'   => false,
            ],
            'actual' => $classes[0],
            'string' => $classes[0],
        ];
    }

    /**
     * @psalm-return iterable<array-key, array{
     *      expect: array<string, mixed>,
     *      actual: string,
     *      string: string
     * }>
     */
    public static function provClassPropertiesEqualButNotIdenticalTo(): iterable
    {
        $classes = [
            get_class(new class() {
                /** @var string */
                public static $emptyString = '';

                /** @var mixed */
                public static $null;

                /** @var string */
                public static $string123 = '123';

                /** @var int */
                public static $int321 = 321;

                /** @var bool */
                public static $boolFalse = false;
            }),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'emptyString' => null,
                'null'        => '',
                'string123'   => 123,
                'int321'      => '321',
                'boolFalse'   => 0,
            ],
            'actual' => $classes[0],
            'string' => $classes[0],
        ];
    }

    /**
     * @psalm-return iterable<array-key, array{
     *      expect: array<string, mixed>,
     *      actual: class-string,
     *      string: string
     * }>
     */
    public static function provClassPropertiesNotEqualTo(): iterable
    {
        $classes = [
            get_class(new class() {
                /** @var string */
                public static $emptyString = '';

                /** @var mixed */
                public static $null;

                /** @var string */
                public static $string123 = '123';

                /** @var int */
                public static $int321 = 321;

                /** @var bool */
                public static $boolFalse = false;
            }),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'emptyString' => 'foo',
                'null'        => 1,
                'string123'   => '321',
                'int321'      => 123,
                'boolFalse'   => true,
            ],
            'actual' => $classes[0],
            'string' => $classes[0],
        ];
    }

    /**
     * @psalm-return iterable<array-key, array{
     *      expect: array<string, mixed>,
     *      actual: mixed,
     *      string: string
     * }>
     */
    public static function provClassPropertiesNotEqualToNonClass(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => 123,
            'string' => '123',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => 'arbitrary string',
            'string' => '\'arbitrary string\'',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => null,
            'string' => 'null',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => ['foo' => 'FOO'],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => new \stdClass(),
            'string' => 'object stdClass',
        ];
    }

    // @codeCoverageIgnoreEnd
}
