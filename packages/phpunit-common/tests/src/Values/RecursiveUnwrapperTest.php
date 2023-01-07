<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\CircularDependencyException;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\RecursiveUnwrapper
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveUnwrapperTest extends TestCase
{
    public const UNIQUE_TAG = RecursiveUnwrapper::UNIQUE_TAG;

    public function createSelectionAggregate(...$args): SelectionWrapperInterface
    {
        $aggregate = $this->createMock(SelectionWrapperInterface::class);
        $aggregate->expects($this->any())
            ->method('getSelection')
            ->willReturn(new Selection(new ArrayValueSelector(), new ExpectedValues(...$args)))
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

    public function provUnwrap(): array
    {
        $actualValues['[baz => BAZ]'] = new ActualValues(['baz' => 'BAZ']);
        $expectValues['[baz => BAZ]'] = new ExpectedValues(['baz' => 'BAZ']);
        $arrayObject['[baz => BAZ]'] = new \ArrayObject(['baz' => 'BAZ']);

        return [
            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'args'   => [],
                'values' => new ExpectedValues([
                ]),
                'expect' => [
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'args'   => [],
                'values' => new ExpectedValues([
                    'foo' => 'FOO',
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'args'   => [],
                'values' => new ExpectedValues([
                    'foo' => 'FOO',
                    'bar' => [
                        'baz' => 'BAZ',
                        'qux' => 'QUX',
                    ],
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    'bar'            => [
                        'baz' => 'BAZ',
                        'qux' => 'QUX',
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'args'   => [],
                'values' => new ExpectedValues([
                    'foo' => 'FOO',
                    'bar' => new ExpectedValues([
                        'baz' => 'BAZ',
                    ]),
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    'bar'            => [
                        'baz'            => 'BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'args'   => [],
                'values' => new ExpectedValues([
                    'foo' => 'FOO',
                    'bar' => $this->createSelectionAggregate([
                        'baz' => 'BAZ',
                    ]),
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    'bar'            => [
                        'baz'            => 'BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'args'   => [],
                'values' => new ExpectedValues([
                    'foo' => 'FOO',
                    'bar' => new ExpectedValues([
                        'qux' => new ExpectedValues(['baz' => 'BAZ']),
                        new ExpectedValues(['fred' => 'FRED']),
                    ]),
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    'bar'            => [
                        'qux'            => [
                            'baz'            => 'BAZ',
                            self::UNIQUE_TAG => true,
                        ],
                        0                => [
                            'fred'           => 'FRED',
                            self::UNIQUE_TAG => true,
                        ],
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'args'   => [],
                'values' => new ExpectedValues([
                    'foo' => 'FOO',
                    'bar' => $this->createSelectionAggregate([
                        'qux' => $this->createSelectionAggregate(['baz' => 'BAZ']),
                        new ExpectedValues(['fred' => 'FRED']),
                    ]),
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    'bar'            => [
                        'qux'            => [
                            'baz'            => 'BAZ',
                            self::UNIQUE_TAG => true,
                        ],
                        0                => [
                            'fred'           => 'FRED',
                            self::UNIQUE_TAG => true,
                        ],
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'args'   => [],
                'values' => new ExpectedValues([
                    'foo' => 'FOO',
                    'bar' => $actualValues['[baz => BAZ]'],
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    'bar'            => $actualValues['[baz => BAZ]'],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'args'   => [],
                'values' => new ActualValues([
                    'foo' => 'FOO',
                    'bar' => $expectValues['[baz => BAZ]'],
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    'bar'            => $expectValues['[baz => BAZ]'],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'args'   => [],
                'values' => new ExpectedValues([
                    'foo' => 'FOO',
                    'bar' => $arrayObject['[baz => BAZ]'],
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    'bar'            => $arrayObject['[baz => BAZ]'],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'args'   => [],
                'values' => new ExpectedValues([
                    'foo' => 'FOO',
                    'bar' => $arrayObject['[baz => BAZ]'],
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    'bar'            => $arrayObject['[baz => BAZ]'],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'args'   => [false], // no tagging
                'values' => new ExpectedValues([
                    'foo' => 'FOO',
                ]),
                'expect' => [
                    'foo' => 'FOO',
                ],
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
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
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'args'   => [false], // no tagging
                'values' => new ExpectedValues([
                    'foo' => 'FOO',
                    'bar' => $this->createSelectionAggregate([
                        'baz' => 'BAZ',
                    ]),
                ]),
                'expect' => [
                    'foo' => 'FOO',
                    'bar' => [
                        'baz' => 'BAZ',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provUnwrap
     */
    public function testUnwrap(array $args, ValuesInterface $values, array $expect): void
    {
        $unwrapper = new RecursiveUnwrapper(...$args);
        self::assertSame($expect, $unwrapper->unwrap($values));
    }

    public function provUnwrapThrowsExceptionOnCircularDependency(): array
    {
        $values['#0'] = new ActualValues([
            'foo' => [
            ],
        ]);
        $values['#0']['foo']['bar'] = $values['#0'];

        $values['#1'] = new ActualValues([
            'foo' => [
                'bar' => [
                ],
            ],
        ]);
        $values['#1']['foo']['bar']['baz'] = $values['#1'];

        $values['#2'] = new ActualValues([
            'foo' => [
                'bar' => new ActualValues([
                    'baz' => 'BAZ',
                ]),
            ],
        ]);
        $values['#2']['foo']['bar']['qux'] = $values['#2']['foo']['bar'];

        $values['#3'] = new ActualValues([
            'foo' => [
                'bar' => new ActualValues([]),
                'baz' => new ActualValues([]),
            ],
        ]);
        $values['#3']['foo']['bar']['qux'] = $values['#3']['foo']['baz'];
        $values['#3']['foo']['baz']['fred'] = $values['#3']['foo']['bar'];

        return [
            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'values' => $values['#0'],
                'key'    => 'bar',
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'values' => $values['#1'],
                'key'    => 'baz',
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'values' => $values['#2'],
                'key'    => 'qux',
            ],

            'RecursiveUnwrapperTest.php:'.__LINE__ => [
                'values' => $values['#3'],
                'key'    => 'fred',
            ],
        ];
    }

    /**
     * @dataProvider provUnwrapThrowsExceptionOnCircularDependency
     *
     * @param mixed $key
     */
    public function testUnwrapThrowsExceptionOnCircularDependency(ValuesInterface $values, $key): void
    {
        $id = is_string($key) ? "'".addslashes($key)."'" : $key;
        $id = preg_quote($id, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested properties at key {$id}\\.$/");

        (new RecursiveUnwrapper())->unwrap($values);
    }
}
// vim: syntax=php sw=4 ts=4 et:
