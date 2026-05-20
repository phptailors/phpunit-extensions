<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultUnwrapper;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\Values\ActualValues;
use Tailors\PHPUnit\Values\DummyValues;
use Tailors\PHPUnit\Values\DummyValuesWrapper;
use Tailors\PHPUnit\Values\ExpectedValues;
use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Recursive\RecursiveResultUnwrapper
 * @covers \Tailors\PHPUnit\Recursive\RecursiveResultUnwrapperVisitor
 * @covers \Tailors\PHPUnit\Values\AbstractValues
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveResultUnwrapperTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function testImplementsRecursiveResultUnwrapperInterface(): void
    {
        self::assertInstanceOf(RecursiveResultUnwrapperInterface::class, new RecursiveResultUnwrapper());
    }

    //
    // unwrap()
    //
    /**
     * @psalm-return iterable<string, array{args: array, values: ValuesInterface, expect: mixed}>
     */
    public static function provUnwrap(): iterable
    {
        $actualValues = ['[baz => BAZ]' => new ActualValues(['baz' => 'BAZ'])];
        $expectValues = ['[baz => BAZ]' => new ExpectedValues(['baz' => 'BAZ'])];
        $arrayObject = ['[baz => BAZ]' => new \ArrayObject(['baz' => 'BAZ'])];

        $tagk = RecursiveResultUnwrapperVisitor::tag();
        $tagg = (new ExpectedValues())->tag();
        $tagd = (new DummyValues(false))->tag();

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new ExpectedValues([]),
            'expect' => [
                $tagk => $tagg,
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new DummyValues(false, []),
            'expect' => [
                $tagk => $tagd,
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new DummyValues(true, []),
            'expect' => [
                $tagk => $tagd,
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new ExpectedValues([
                'foo' => 'FOO',
            ]),
            'expect' => [
                'foo' => 'FOO',
                $tagk => $tagg,
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [false], // no tagging
            'values' => new ExpectedValues([
                'foo' => 'FOO',
            ]),
            'expect' => [
                'foo' => 'FOO',
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [false], // no tagging
            'values' => new ExpectedValues([
                'foo' => new ExpectedValues([]),
            ]),
            'expect' => [
                'foo' => [],
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => [
                    'baz' => 'BAZ',
                    'qux' => 'QUX',
                ],
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => [
                    'baz' => 'BAZ',
                    'qux' => 'QUX',
                ],
                $tagk => $tagg,
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => new ExpectedValues([
                    'baz' => 'BAZ',
                ]),
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => [
                    'baz' => 'BAZ',
                    $tagk => $tagg,
                ],
                $tagk => $tagg,
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => new DummyValuesWrapper(new ExpectedValues([
                    'baz' => 'BAZ',
                ])),
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => [
                    'baz' => 'BAZ',
                    $tagk => $tagg,
                ],
                $tagk => $tagg,
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => new ExpectedValues([
                    'qux' => new ExpectedValues(['baz' => 'BAZ']),
                    new ExpectedValues(['fred' => 'FRED']),
                ]),
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => [
                    'qux' => [
                        'baz' => 'BAZ',
                        $tagk => $tagg,
                    ],
                    0 => [
                        'fred' => 'FRED',
                        $tagk  => $tagg,
                    ],
                    $tagk => $tagg,
                ],
                $tagk => $tagg,
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => new DummyValuesWrapper(new ExpectedValues([
                    'qux' => new DummyValuesWrapper(new ExpectedValues(['baz' => 'BAZ'])),
                    new ExpectedValues(['fred' => 'FRED']),
                ])),
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => [
                    'qux' => [
                        'baz' => 'BAZ',
                        $tagk => $tagg,
                    ],
                    0 => [
                        'fred' => 'FRED',
                        $tagk  => $tagg,
                    ],
                    $tagk => $tagg,
                ],
                $tagk => $tagg,
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => $actualValues['[baz => BAZ]'],
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => $actualValues['[baz => BAZ]'],
                $tagk => $tagg,
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new ActualValues([
                'foo' => 'FOO',
                'bar' => $expectValues['[baz => BAZ]'],
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => $expectValues['[baz => BAZ]'],
                $tagk => $tagg,
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => $arrayObject['[baz => BAZ]'],
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => $arrayObject['[baz => BAZ]'],
                $tagk => $tagg,
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => $arrayObject['[baz => BAZ]'],
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => $arrayObject['[baz => BAZ]'],
                $tagk => $tagg,
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [false], // no tagging
            'values' => new ExpectedValues([
                'foo' => 'FOO',
            ]),
            'expect' => [
                'foo' => 'FOO',
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [false], // no tagging
            'values' => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => new ExpectedValues([
                    'baz' => 'BAZ',
                ]),
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => [
                    'baz' => 'BAZ',
                ],
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [false], // no tagging
            'values' => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => new DummyValuesWrapper(new ExpectedValues([
                    'baz' => 'BAZ',
                ])),
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => [
                    'baz' => 'BAZ',
                ],
            ],
        ];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => new DummyValuesWrapper(new DummyValues(false, [
                    'baz' => 'BAZ',
                ])),
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => [
                    'baz' => 'BAZ',
                    $tagk => $tagd,
                ],
                $tagk => $tagg,
            ],
        ];
    }

    /**
     * @dataProvider provUnwrap
     *
     * @param mixed $expect
     */
    public function testUnwrap(array $args, ValuesInterface $values, $expect): void
    {
        $unwrapper = new RecursiveResultUnwrapper(...$args);
        self::assertSame($expect, $unwrapper->unwrap($values));
    }

    /**
     * @psalm-return iterable<string, array{values: ValuesInterface, path: string}>
     */
    public static function provUnwrapThrowsExceptionOnCircularDependency(): iterable
    {
        //
        // 01
        //
        $v01 = new ActualValues([
            'foo' => [],
        ]);
        $v01['foo']['bar'] = $v01;

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'values' => $v01,
            'path'   => "['foo']['bar']",
        ];

        //
        // 02
        //

        $v02 = new ActualValues([
            'foo' => [
                'bar' => [],
            ],
        ]);
        $v02['foo']['bar']['baz'] = $v02;

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'values' => $v02,
            'path'   => "['foo']['bar']['baz']",
        ];

        //
        // 03
        //

        $v03 = new ActualValues([
            'foo' => [
                'bar' => new ActualValues([
                    'baz' => 'BAZ',
                ]),
            ],
        ]);
        $v03['foo']['bar']['qux'] = $v03['foo']['bar'];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'values' => $v03,
            'path'   => "['foo']['bar']['qux']",
        ];

        //
        // 04
        //

        $v04 = new ActualValues([
            'foo' => [
                'bar' => new ActualValues([]),
                'baz' => new ActualValues([]),
            ],
        ]);
        $v04['foo']['bar']['qux'] = $v04['foo']['baz'];
        $v04['foo']['baz']['fred'] = $v04['foo']['bar'];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'values' => $v04,
            'path'   => "['foo']['bar']['qux']['fred']",
        ];

        //
        // 05
        //

        $v05 = new ActualValues([
            'foo' => [
                'bar' => [],
                'baz' => [],
            ],
        ]);
        $v05['foo']['baz'] = &$v05['foo'];

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'values' => $v05,
            'path'   => "['foo']['baz']",
        ];

        //
        // 06
        //

        $v06 = [
            'foo' => new ActualValues([
                'bar' => [],
            ]),
        ];
        $v06['foo']['bar']['baz'] = &$v06;

        yield 'RecursiveResultUnwrapperTest.php:'.__LINE__ => [
            'values' => $v06['foo'],
            'path'   => "['bar']['baz']['foo']",
        ];
    }

    /**
     * @dataProvider provUnwrapThrowsExceptionOnCircularDependency
     */
    public function testUnwrapThrowsExceptionOnCircularDependency(ValuesInterface $values, string $path): void
    {
        $rePath = preg_quote($path, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested values at \\\$values{$rePath}\\.$/");

        (new RecursiveResultUnwrapper())->unwrap($values);
    }
}
// vim: syntax=php sw=4 ts=4 et:
