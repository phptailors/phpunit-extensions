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
 * @psalm-type CtorArgs = list{0: array, 1?: int}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
trait ProvRecursivelyKsortedArrayTrait
{
    /**
     * @psalm-return iterable<string, array{
     *      expect: array,
     *      actual: mixed,
     *      string: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provRecursivelyKsortedArrayIdenticalTo(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [],
            'actual' => [],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['a' => 'A', 'b' => 'B'],
            'actual' => ['a' => 'A', 'b' => 'B'],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['b' => 'B', 'a' => 'A'],
            'actual' => ['a' => 'A', 'b' => 'B'],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['a' => 'A', 'b' => 'B'],
            'actual' => ['b' => 'B', 'a' => 'A'],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['a' => false, 'b' => 0, 'c' => '', 'd' => null],
            'actual' => ['a' => false, 'b' => 0, 'c' => '', 'd' => null],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'a' => [
                    'd' => 'AD',
                    'c' => 'AC',
                ],
                'b' => [
                    'f' => 'BF',
                    'e' => 'BE',
                ],
            ],
            'actual' => [
                'b' => [
                    'e' => 'BE',
                    'f' => 'BF',
                ],
                'a' => [
                    'c' => 'AC',
                    'd' => 'AD',
                ],
            ],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['a' => ['b' => 'B']],
            'actual' => ['a' => ['b' => 'B']],
            'string' => 'array',
        ];

        $arrObj = new \ArrayObject(['d' => ['f' => 'DF', 'e' => 'DE'], 'c' => 'C']);

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'a' => $arrObj,
                'b' => 'B',
            ],
            'actual' => [
                'b' => 'B',
                'a' => $arrObj,
            ],
            'string' => 'array',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'c' => new ArrayValuesSelection([
                    'f' => [
                        'h' => 'CFH',
                        'g' => 'CFG',
                    ],
                    'e' => 'CE',
                ]),
                'b' => [
                    'j' => 'BJ',
                    'i' => 'BI',
                ],
                'a' => 'A',
            ],
            'actual' => [
                'c' => [
                    'f' => [
                        'g' => 'CFG',
                        'h' => 'CFH',
                    ],
                    'e' => 'CE',
                    'd' => 'CD',
                ],
                'b' => [
                    'j' => 'BJ',
                    'i' => 'BI',
                ],
                'a' => 'A',
            ],
            'string' => 'array',
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      expect: array,
     *      actual: mixed,
     *      string: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provRecursivelyKsortedArrayEqualButNotIdenticalTo(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
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

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => [
                'a' => [
                    'd' => 'AD',
                    'c' => null,
                ],
                'b' => [
                    'f' => 'BF',
                    'e' => 321,
                ],
            ],
            'actual' => [
                'b' => [
                    'e' => '321',
                    'f' => 'BF',
                ],
                'a' => [
                    'c' => 0,
                    'd' => 'AD',
                ],
            ],
            'string' => 'array',
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      expect: array,
     *      actual: mixed,
     *      string: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provRecursivelyKsortedArrayNotEqualTo(): iterable
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
            'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'GEZ']],
            'actual' => 'arbitrary string',
            'string' => '\'arbitrary string\'',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expect' => ['foo' => 'FOO', 'arr' => ['bar' => 'GEZ']],
            'actual' => \Exception::class,
            'string' => \Exception::class,
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      expect: array,
     *      actual: mixed,
     *      string: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provRecursivelyKsortedArrayNotEqualToNonArray(): iterable
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
