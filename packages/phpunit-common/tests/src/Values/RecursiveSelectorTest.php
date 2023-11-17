<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\RecursiveSelector
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveSelectorTest extends TestCase
{
    public const UNIQUE_TAG = RecursiveUnwrapper::UNIQUE_TAG;

    public static function createSelection(...$args): ExpectedValuesSelection
    {
        return new ExpectedValuesSelection(new ArrayValueSelector(), ...$args);
    }

    public static function createSelectionAggregate(TestCase $test, ...$args): SelectionWrapperInterface
    {
        $aggregate = $test->createMock(SelectionWrapperInterface::class);
        $aggregate->expects($test->any())
            ->method('getSelection')
            ->willReturn(self::createSelection(...$args))
        ;

        return $aggregate;
    }

    //
    //
    // TESTS
    //
    //

    public function testImplementsRecursiveSelectorInterface(): void
    {
        $selection = $this->createMock(SelectionInterface::class);
        self::assertInstanceOf(RecursiveSelectorInterface::class, new RecursiveSelector($selection));
    }

    //
    // selectProperties()
    //

    public static function provSelectProperties(): array
    {
        return [
            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => function (TestCase $test) { return self::createSelection([]); },
                'subject'   => [],
                'expect'    => [
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => function (TestCase $test) {
                    return self::createSelection([
                        'foo' => 'e:FOO',
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                ],
                'expect' => [
                    'foo'            => 'a:FOO',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => function (TestCase $test) {
                    return self::createSelection([
                        'foo' => 'e:FOO',
                        'bar' => [
                            'baz' => 'e:BAZ',
                        ],
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz' => 'a:BAZ',
                    ],
                ],
                'expect' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz' => 'a:BAZ',
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => function (TestCase $test) {
                    return self::createSelection([
                        'foo' => 'e:FOO',
                        'bar' => [
                            'baz' => 'e:BAZ',
                        ],
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => 'a:BAR',
                ],
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar'            => 'a:BAR',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => function (TestCase $test) {
                    return self::createSelection([
                        'foo' => 'e:FOO',
                        'bar' => self::createSelection([
                            'baz' => 'e:BAZ',
                        ]),
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz' => 'a:BAZ',
                        'qux' => 'a:QUX',
                    ],
                ],
                'expect' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz'            => 'a:BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => function (TestCase $test) {
                    return self::createSelection([
                        'foo' => 'e:FOO',
                        'bar' => self::createSelectionAggregate($test, [
                            'baz' => 'e:BAZ',
                        ]),
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz' => 'a:BAZ',
                        'qux' => 'a:QUX',
                    ],
                ],
                'expect' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz'            => 'a:BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => function (TestCase $test) {
                    return self::createSelection([
                        'foo' => 'e:FOO',
                        'bar' => self::createSelection([
                            'baz' => 'e:BAZ',
                        ]),
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => new \ArrayObject([
                        'baz' => 'a:BAZ',
                        'qux' => 'a:QUX',
                    ]),
                ],
                'expect' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz'            => 'a:BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => function (TestCase $test) {
                    return self::createSelection([
                        'foo' => 'e:FOO',
                        'bar' => self::createSelectionAggregate($test, [
                            'baz' => 'e:BAZ',
                        ]),
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => new \ArrayObject([
                        'baz' => 'a:BAZ',
                        'qux' => 'a:QUX',
                    ]),
                ],
                'expect' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz'            => 'a:BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => function (TestCase $test) {
                    return self::createSelection([
                        'foo' => 'e:FOO',
                        'bar' => self::createSelection([
                            'baz' => 'e:BAZ',
                        ]),
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => 'a:BAR',
                ],
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar'            => 'a:BAR',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => function (TestCase $test) {
                    return self::createSelection([
                        'foo' => 'e:FOO',
                        'bar' => self::createSelectionAggregate($test, [
                            'baz' => 'e:BAZ',
                        ]),
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => 'a:BAR',
                ],
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar'            => 'a:BAR',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => function (TestCase $test) {
                    return self::createSelection([
                        'foo' => 'e:FOO',
                        'bar' => self::createSelection([
                            'baz' => 'e:BAZ',
                        ]),
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => 'a:BAR',
                ],
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar'            => 'a:BAR',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => function (TestCase $test) {
                    return self::createSelection([
                        'foo' => 'e:FOO',
                        'bar' => self::createSelectionAggregate($test, [
                            'baz' => 'e:BAZ',
                        ]),
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => 'a:BAR',
                ],
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar'            => 'a:BAR',
                    self::UNIQUE_TAG => true,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provSelectProperties
     *
     * @param \Closure(TestCase):ExpectedValuesSelection $selection
     * @param mixed                                      $subject
     */
    public function testSelectProperties(\Closure $selection, $subject, array $expect): void
    {
        $selector = new RecursiveSelector($selection($this));
        $unwrapper = new RecursiveUnwrapper();
        $selected = $selector->select($subject);
        self::assertInstanceOf(ActualValues::class, $selected);
        self::assertSame($expect, $unwrapper->unwrap($selected));
    }
}
// vim: syntax=php sw=4 ts=4 et:
