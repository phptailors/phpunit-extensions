<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Values\ActualValues;
use Tailors\PHPUnit\Values\RecursiveUnwrapper;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\RecursiveSorter
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveSoerterTest extends TestCase
{
    public const UNIQUE_TAG = RecursiveUnwrapper::UNIQUE_TAG;

    public static function createSorting(...$args): ExpectedValuesSorting
    {
        return new ExpectedValuesSorting(new ArrayKsorter(\SORT_REGULAR), ...$args);
    }

    public static function createSortingAggregate(TestCase $test, ...$args): SortingWrapperInterface
    {
        $aggregate = $test->createMock(SortingWrapperInterface::class);
        $aggregate->expects($test->any())
            ->method('getSorting')
            ->willReturn(self::createSorting(...$args))
        ;

        return $aggregate;
    }

    //
    //
    // TESTS
    //
    //

    public function testImplementsRecursiveSorterInterface(): void
    {
        $sorting = $this->createMock(SortingInterface::class);
        self::assertInstanceOf(RecursiveSorterInterface::class, new RecursiveSorter($sorting));
    }

    //
    // sorted()
    //

    public static function provSorted(): array
    {
        return [
            'RecursiveSorterTest.php:'.__LINE__ => [
                'sorting' => function (TestCase $test) { return self::createSorting([]); },
                'subject'   => [],
                'expect'    => [
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSorterTest.php:'.__LINE__ => [
                'sorting' => function (TestCase $test) {
                    return self::createSorting([
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

            'RecursiveSorterTest.php:'.__LINE__ => [
                'sorting' => function (TestCase $test) {
                    return self::createSorting([
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

            'RecursiveSorterTest.php:'.__LINE__ => [
                'sorting' => function (TestCase $test) {
                    return self::createSorting([
                        'foo' => 'e:FOO',
                        'bar' => [
                            'gez' => 'e:GEZ',
                            'baz' => 'e:BAZ',
                        ],
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'gez' => 'a:GEZ',
                        'baz' => 'a:BAZ',
                    ],
                ],
                'expect' => [
                    'bar' => [
                        'gez' => 'a:GEZ',
                        'baz' => 'a:BAZ',
                    ],
                    'foo' => 'a:FOO',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSorterTest.php:'.__LINE__ => [
                'sorting' => function (TestCase $test) {
                    return self::createSorting([
                        'foo' => 'e:FOO',
                        'bar' => self::createSorting([
                            'gez' => 'e:GEZ',
                            'baz' => 'e:BAZ',
                        ]),
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'gez' => 'a:GEZ',
                        'baz' => 'a:BAZ',
                    ],
                ],
                'expect' => [
                    'bar' => [
                        'baz' => 'a:BAZ',
                        'gez' => 'a:GEZ',
                        self::UNIQUE_TAG => true,
                    ],
                    'foo' => 'a:FOO',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSorterTest.php:'.__LINE__ => [
                'sorting' => function (TestCase $test) {
                    return self::createSorting([
                        'foo' => 'e:FOO',
                        'bar' => self::createSorting([
                            'gez' => 'e:GEZ',
                            'baz' => 'e:BAZ',
                        ]),
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'gez' => 'a:GEZ',
                        'baz' => 'a:BAZ',
                    ],
                    'gez' => [
                        'qux' => 'b:QUX',
                        'baz' => 'b:BAZ',
                    ],
                ],
                'expect' => [
                    'bar' => [
                        'baz' => 'a:BAZ',
                        'gez' => 'a:GEZ',
                        self::UNIQUE_TAG => true,
                    ],
                    'foo' => 'a:FOO',
                    'gez' => [
                        'qux' => 'b:QUX',
                        'baz' => 'b:BAZ',
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSorterTest.php:'.__LINE__ => [
                'sorting' => function (TestCase $test) {
                    return self::createSorting([
                        'foo' => 'e:FOO',
                        'bar' => self::createSorting([
                            'gez' => 'e:GEZ',
                            'baz' => 'e:BAZ',
                        ]),
                        'gez' => [
                            'qux' => 'f:QUX',
                            'baz' => 'f:BAZ',
                        ],
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'gez' => 'a:GEZ',
                        'baz' => 'a:BAZ',
                    ],
                    'gez' => [
                        'qux' => 'b:QUX',
                        'baz' => 'b:BAZ',
                    ],
                ],
                'expect' => [
                    'bar' => [
                        'baz' => 'a:BAZ',
                        'gez' => 'a:GEZ',
                        self::UNIQUE_TAG => true,
                    ],
                    'foo' => 'a:FOO',
                    'gez' => [
                        'qux' => 'b:QUX',
                        'baz' => 'b:BAZ',
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSorterTest.php:'.__LINE__ => [
                'sorting' => function (TestCase $test) {
                    return self::createSorting([
                        'foo' => 'e:FOO',
                        'bar' => self::createSorting([
                            'gez' => 'e:GEZ',
                            'baz' => 'e:BAZ',
                        ]),
                        'gez' => self::createSorting([
                            'qux' => 'f:QUX',
                            'baz' => 'f:BAZ',
                        ]),
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'gez' => 'a:GEZ',
                        'baz' => 'a:BAZ',
                    ],
                    'gez' => [
                        'qux' => 'b:QUX',
                        'baz' => 'b:BAZ',
                    ],
                ],
                'expect' => [
                    'bar' => [
                        'baz' => 'a:BAZ',
                        'gez' => 'a:GEZ',
                        self::UNIQUE_TAG => true,
                    ],
                    'foo' => 'a:FOO',
                    'gez' => [
                        'baz' => 'b:BAZ',
                        'qux' => 'b:QUX',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

//            'RecursiveSorterTest.php:'.__LINE__ => [
//                'sorting' => function (TestCase $test) {
//                    return self::createSorting([
//                        'foo' => 'e:FOO',
//                        'bar' => [
//                            'baz' => 'e:BAZ',
//                        ],
//                    ]);
//                },
//                'subject' => [
//                    'foo' => 'a:FOO',
//                    'bar' => 'a:BAR',
//                ],
//                'expect' => [
//                    'foo'            => 'a:FOO',
//                    'bar'            => 'a:BAR',
//                    self::UNIQUE_TAG => true,
//                ],
//            ],
//
//            'RecursiveSorterTest.php:'.__LINE__ => [
//                'sorting' => function (TestCase $test) {
//                    return self::createSorting([
//                        'foo' => 'e:FOO',
//                        'bar' => self::createSorting([
//                            'baz' => 'e:BAZ',
//                        ]),
//                    ]);
//                },
//                'subject' => [
//                    'foo' => 'a:FOO',
//                    'bar' => [
//                        'baz' => 'a:BAZ',
//                        'qux' => 'a:QUX',
//                    ],
//                ],
//                'expect' => [
//                    'foo' => 'a:FOO',
//                    'bar' => [
//                        'baz'            => 'a:BAZ',
//                        self::UNIQUE_TAG => true,
//                    ],
//                    self::UNIQUE_TAG => true,
//                ],
//            ],
//
//            'RecursiveSorterTest.php:'.__LINE__ => [
//                'sorting' => function (TestCase $test) {
//                    return self::createSorting([
//                        'foo' => 'e:FOO',
//                        'bar' => self::createSortingAggregate($test, [
//                            'baz' => 'e:BAZ',
//                        ]),
//                    ]);
//                },
//                'subject' => [
//                    'foo' => 'a:FOO',
//                    'bar' => [
//                        'baz' => 'a:BAZ',
//                        'qux' => 'a:QUX',
//                    ],
//                ],
//                'expect' => [
//                    'foo' => 'a:FOO',
//                    'bar' => [
//                        'baz'            => 'a:BAZ',
//                        self::UNIQUE_TAG => true,
//                    ],
//                    self::UNIQUE_TAG => true,
//                ],
//            ],
//
//            'RecursiveSorterTest.php:'.__LINE__ => [
//                'sorting' => function (TestCase $test) {
//                    return self::createSorting([
//                        'foo' => 'e:FOO',
//                        'bar' => self::createSorting([
//                            'baz' => 'e:BAZ',
//                        ]),
//                    ]);
//                },
//                'subject' => [
//                    'foo' => 'a:FOO',
//                    'bar' => new \ArrayObject([
//                        'baz' => 'a:BAZ',
//                        'qux' => 'a:QUX',
//                    ]),
//                ],
//                'expect' => [
//                    'foo' => 'a:FOO',
//                    'bar' => [
//                        'baz'            => 'a:BAZ',
//                        self::UNIQUE_TAG => true,
//                    ],
//                    self::UNIQUE_TAG => true,
//                ],
//            ],
//
//            'RecursiveSorterTest.php:'.__LINE__ => [
//                'sorting' => function (TestCase $test) {
//                    return self::createSorting([
//                        'foo' => 'e:FOO',
//                        'bar' => self::createSortingAggregate($test, [
//                            'baz' => 'e:BAZ',
//                        ]),
//                    ]);
//                },
//                'subject' => [
//                    'foo' => 'a:FOO',
//                    'bar' => new \ArrayObject([
//                        'baz' => 'a:BAZ',
//                        'qux' => 'a:QUX',
//                    ]),
//                ],
//                'expect' => [
//                    'foo' => 'a:FOO',
//                    'bar' => [
//                        'baz'            => 'a:BAZ',
//                        self::UNIQUE_TAG => true,
//                    ],
//                    self::UNIQUE_TAG => true,
//                ],
//            ],
//
//            'RecursiveSorterTest.php:'.__LINE__ => [
//                'sorting' => function (TestCase $test) {
//                    return self::createSorting([
//                        'foo' => 'e:FOO',
//                        'bar' => self::createSorting([
//                            'baz' => 'e:BAZ',
//                        ]),
//                    ]);
//                },
//                'subject' => [
//                    'foo' => 'a:FOO',
//                    'bar' => 'a:BAR',
//                ],
//                'expect' => [
//                    'foo'            => 'a:FOO',
//                    'bar'            => 'a:BAR',
//                    self::UNIQUE_TAG => true,
//                ],
//            ],
//
//            'RecursiveSorterTest.php:'.__LINE__ => [
//                'sorting' => function (TestCase $test) {
//                    return self::createSorting([
//                        'foo' => 'e:FOO',
//                        'bar' => self::createSortingAggregate($test, [
//                            'baz' => 'e:BAZ',
//                        ]),
//                    ]);
//                },
//                'subject' => [
//                    'foo' => 'a:FOO',
//                    'bar' => 'a:BAR',
//                ],
//                'expect' => [
//                    'foo'            => 'a:FOO',
//                    'bar'            => 'a:BAR',
//                    self::UNIQUE_TAG => true,
//                ],
//            ],

            'RecursiveSorterTest.php:'.__LINE__ => [
                'sorting' => function (TestCase $test) {
                    return self::createSorting([
                        'foo' => 'e:FOO',
                        'bar' => self::createSorting([
                            'baz' => 'e:BAZ',
                        ]),
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => 'a:BAR',
                ],
                'expect' => [
                    'bar'            => 'a:BAR',
                    'foo'            => 'a:FOO',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSorterTest.php:'.__LINE__ => [
                'sorting' => function (TestCase $test) {
                    return self::createSorting([
                        'foo' => 'e:FOO',
                        'bar' => self::createSortingAggregate($test, [
                            'baz' => 'e:BAZ',
                        ]),
                    ]);
                },
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => 'a:BAR',
                ],
                'expect' => [
                    'bar'            => 'a:BAR',
                    'foo'            => 'a:FOO',
                    self::UNIQUE_TAG => true,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provSorted
     *
     * @param \Closure(TestCase):ExpectedValuesSorting $sorting
     * @param mixed                                    $subject
     */
    public function testSorted(\Closure $sorting, $subject, array $expect): void
    {
        $sorter = new RecursiveSorter($sorting($this));
        $unwrapper = new RecursiveUnwrapper();
        $sorted = $sorter->sorted($subject);
        self::assertInstanceOf(ActualValues::class, $sorted);
        self::assertSame($expect, $unwrapper->unwrap($sorted));
    }
}
// vim: syntax=php sw=4 ts=4 et:
