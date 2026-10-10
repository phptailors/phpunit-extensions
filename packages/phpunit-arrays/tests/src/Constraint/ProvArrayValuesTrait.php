<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Arrays\ArrayValuesSelection;
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type CreateConstraintArgs = list{ArrayLike}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
trait ProvArrayValuesTrait
{
    /**
     * @psalm-return iterable<string,array {
     *      expect: array,
     *      actual: array|\ArrayObject,
     *      string: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provArrayValuesIdenticalTo(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [],
            'actual' => [],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [],
            'actual' => ['foo' => 'FOO', 'bar' => 'BAR'],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => ['foo' => 'FOO'],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => ['foo' => 'FOO', 'bar' => 'BAR'],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => new \ArrayObject(['foo' => 'FOO']),
            'string' => 'object ArrayObject',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => new \ArrayObject(['foo' => 'FOO', 'bar' => 'BAR']),
            'string' => 'object ArrayObject',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'bar' => new ArrayValuesSelection(['gez' => 'GEZ'])],
            'actual' => ['foo' => 'FOO', 'bar' => ['baz' => 'BAZ', 'gez' => 'GEZ', 'qux' => 'QUX']],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'bar' => new ArrayValuesSelection(['gez' => 'GEZ'])],
            'actual' => ['foo' => 'FOO', 'bar' => new \ArrayObject(['baz' => 'BAZ', 'gez' => 'GEZ', 'qux' => 'QUX'])],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'bar' => new ArrayValuesSelection(['gez' => 'GEZ'])],
            'actual' => new \ArrayObject(['foo' => 'FOO', 'bar' => ['baz' => 'BAZ', 'gez' => 'GEZ', 'qux' => 'QUX']]),
            'string' => 'object ArrayObject',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'bar' => ['baz' => 'BAZ', 'gez' => new ArrayValuesSelection(['qux' => 'QUX'])]],
            'actual' => ['foo' => 'FOO', 'bar' => ['baz' => 'BAZ', 'gez' => ['cop' => 'COP', 'qux' => 'QUX', 'dig' => 'DIG']]],
            'string' => 'array',
        ];
    }

    /**
     * @psalm-return iterable<string,array {
     *      expect: array,
     *      actual: array|\ArrayObject,
     *      string: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provArrayValuesEqualButNotIdenticalTo(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
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
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'arr' => new \ArrayObject(['bar' => 'BAR'])],
            'actual' => ['foo' => 'FOO', 'arr' => new \ArrayObject(['bar' => 'BAR'])],
            'string' => 'array',
        ];
    }

    /**
     * @psalm-return iterable<string,array {
     *      expect: array,
     *      actual: array|\ArrayObject,
     *      string: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provArrayValuesNotEqualTo(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'bar' => 'GEZ', 'int' => 21],
            'actual' => ['foo' => 'FOO', 'bar' => 'BAR', 'int' => 21],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'GEZ']],
            'actual' => ['foo' => 'FOO', 'arr' => ['bar' => 'BAR']],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'BAR']],
            'actual' => ['foo' => 'FOO', 'arr' => new \ArrayObject(['bar' => 'BAR'])],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'arr' => new \ArrayObject(['bar' => 'BAR'])],
            'actual' => ['foo' => 'FOO', 'arr' => ['bar' => 'BAR']],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'bar' => ['baz' => 'BAZ', 'gez' => new ArrayValuesSelection(['qux' => 'QUX'])]],
            'actual' => ['foo' => 'FOO', 'bar' => ['baz' => 'BAZ', 'gez' => ['qux' => 'QUX'], 'ext' => 'EXT']],
            'string' => 'array',
        ];
    }

    /**
     * @psalm-return iterable<string,array {
     *      expect: array,
     *      actual: mixed,
     *      string: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provArrayValuesNotEqualToNonArray(): iterable
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
            'actual' => new \stdClass(),
            'string' => 'object stdClass',
        ];
    }
}
