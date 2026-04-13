<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\CircularDependencyException;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(RecursiveUnwrapper::class)]
final class RecursiveUnwrapperTest extends TestCase
{
    public const UNIQUE_TAG = RecursiveUnwrapper::UNIQUE_TAG;

    /**
     * @param mixed $args
     */
    public static function createValuesWrapper(TestCase $test, ...$args): ValuesWrapperInterface
    {
        $aggregate = $test->createMock(SelectionWrapperInterface::class);
        $aggregate->expects($test->any())
            ->method('getValues')
            ->willReturn(new ExpectedValuesSelection(new ArrayValueSelector(), new ExpectedValues(...$args)))
        ;

        return $aggregate;
    }

    //
    //
    // TESTS
    //
    //

    public function testImplementsRecursiveUnwrapperInterface(): void
    {
        self::assertInstanceOf(RecursiveUnwrapperInterface::class, new RecursiveUnwrapper());
    }

    //
    // unwrap()
    //

    /**
     * @return iterable<string, array{args: array, values: \Closure(TestCase):ValuesInterface, expect: array}>
     */
    public static function provUnwrap(): iterable
    {
        $actualValues = ['[baz => BAZ]' => new ActualValues(['baz' => 'BAZ'])];
        $expectValues = ['[baz => BAZ]' => new ExpectedValues(['baz' => 'BAZ'])];
        $arrayObject = ['[baz => BAZ]' => new \ArrayObject(['baz' => 'BAZ'])];

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => fn (TestCase $test) => new ExpectedValues([]),
            'expect' => [
                self::UNIQUE_TAG => true,
            ],
        ];

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => fn (TestCase $test) => new ExpectedValues([
                'foo' => 'FOO',
            ]),
            'expect' => [
                'foo'            => 'FOO',
                self::UNIQUE_TAG => true,
            ],
        ];

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => fn (TestCase $test) => new ExpectedValues([
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
                self::UNIQUE_TAG => true,
            ],
        ];

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => fn (TestCase $test) => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => new ExpectedValues([
                    'baz' => 'BAZ',
                ]),
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => [
                    'baz'            => 'BAZ',
                    self::UNIQUE_TAG => true,
                ],
                self::UNIQUE_TAG => true,
            ],
        ];

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => fn (TestCase $test) => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => self::createValuesWrapper($test, [
                    'baz' => 'BAZ',
                ]),
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => [
                    'baz'            => 'BAZ',
                    self::UNIQUE_TAG => true,
                ],
                self::UNIQUE_TAG => true,
            ],
        ];

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => fn (TestCase $test) => new ExpectedValues([
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
                        'baz'            => 'BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    0 => [
                        'fred'           => 'FRED',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
                self::UNIQUE_TAG => true,
            ],
        ];

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => fn (TestCase $test) => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => self::createValuesWrapper($test, [
                    'qux' => self::createValuesWrapper($test, ['baz' => 'BAZ']),
                    new ExpectedValues(['fred' => 'FRED']),
                ]),
            ]),
            'expect' => [
                'foo' => 'FOO',
                'bar' => [
                    'qux' => [
                        'baz'            => 'BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    0 => [
                        'fred'           => 'FRED',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
                self::UNIQUE_TAG => true,
            ],
        ];

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => fn (TestCase $test) => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => $actualValues['[baz => BAZ]'],
            ]),
            'expect' => [
                'foo'            => 'FOO',
                'bar'            => $actualValues['[baz => BAZ]'],
                self::UNIQUE_TAG => true,
            ],
        ];

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => fn (TestCase $test) => new ActualValues([
                'foo' => 'FOO',
                'bar' => $expectValues['[baz => BAZ]'],
            ]),
            'expect' => [
                'foo'            => 'FOO',
                'bar'            => $expectValues['[baz => BAZ]'],
                self::UNIQUE_TAG => true,
            ],
        ];

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => fn (TestCase $test) => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => $arrayObject['[baz => BAZ]'],
            ]),
            'expect' => [
                'foo'            => 'FOO',
                'bar'            => $arrayObject['[baz => BAZ]'],
                self::UNIQUE_TAG => true,
            ],
        ];

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [],
            'values' => fn (TestCase $test) => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => $arrayObject['[baz => BAZ]'],
            ]),
            'expect' => [
                'foo'            => 'FOO',
                'bar'            => $arrayObject['[baz => BAZ]'],
                self::UNIQUE_TAG => true,
            ],
        ];

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [false], // no tagging
            'values' => fn (TestCase $test) => new ExpectedValues([
                'foo' => 'FOO',
            ]),
            'expect' => [
                'foo' => 'FOO',
            ],
        ];

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [false], // no tagging
            'values' => fn (TestCase $test) => new ExpectedValues([
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

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'args'   => [false], // no tagging
            'values' => fn (TestCase $test) => new ExpectedValues([
                'foo' => 'FOO',
                'bar' => self::createValuesWrapper($test, [
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
    }

    /**
     * @param \Closure(TestCase):ValuesInterface $values
     */
    #[DataProvider('provUnwrap')]
    public function testUnwrap(array $args, \Closure $values, array $expect): void
    {
        $unwrapper = new RecursiveUnwrapper(...$args);
        self::assertSame($expect, $unwrapper->unwrap($values($this)));
    }

    /**
     * @return iterable<string, array{values: ValuesInterface, path: string}>
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

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'values' => $v01,
            'path'   => '["foo"]["bar"]',
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

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'values' => $v02,
            'path'   => '["foo"]["bar"]["baz"]',
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

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'values' => $v03,
            'path'   => '["foo"]["bar"]["qux"]',
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

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'values' => $v04,
            'path'   => '["foo"]["bar"]["qux"]["fred"]',
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

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'values' => $v05,
            'path'   => '["foo"]["baz"]',
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

        yield 'RecursiveUnwrapperTest.php:'.__LINE__ => [
            'values' => $v06['foo'],
            'path'   => '["bar"]["baz"]["foo"]',
        ];
    }

    #[DataProvider('provUnwrapThrowsExceptionOnCircularDependency')]
    public function testUnwrapThrowsExceptionOnCircularDependency(ValuesInterface $values, string $path): void
    {
        $rePath = preg_quote($path, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested values at \\\$values{$rePath}\\.$/");

        (new RecursiveUnwrapper())->unwrap($values);
    }
}
// vim: syntax=php sw=4 ts=4 et:
