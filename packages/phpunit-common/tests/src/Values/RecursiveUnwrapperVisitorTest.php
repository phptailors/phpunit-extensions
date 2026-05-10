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
use Tailors\PHPUnit\CircularDependencyException;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\RecursiveUnwrapperVisitor
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArgsEnter = array{node: array|ValuesInterface, path: list<array-key>, stack: list<array|ValuesInterface>}
 * @psalm-type ArgsVisit = array{node: mixed, path: list<array-key>, stack: list<array|ValuesInterface>, iterating: bool}
 * @psalm-type StackItem = RecursiveUnwrapperStackItem
 */
final class RecursiveUnwrapperVisitorTest extends TestCase
{
    public const UNIQUE_TAG = RecursiveUnwrapperVisitor::UNIQUE_TAG;

    //
    //
    // TESTS
    //
    //

    public function testImplementsRecursiveVisitorInterface(): void
    {
        self::assertInstanceOf(RecursiveVisitorInterface::class, new RecursiveUnwrapperVisitor());
    }

    public function testInitialResult(): void
    {
        $visitor = new RecursiveUnwrapperVisitor();
        $this->assertSame([], $visitor->result());
    }

    /**
     * @return iterable<string, array{stack: list<StackItem>, expect: string}>
     */
    public static function provCycle(): iterable
    {
        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'stack'  => [],
            'expect' => '',
        ];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'stack' => [
                new RecursiveUnwrapperStackItem([], 'foo'),
                new RecursiveUnwrapperStackItem([], 3),
                new RecursiveUnwrapperStackItem([], 'bar'),
            ],
            'expect' => "['foo'][3]['bar']",
        ];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'stack' => [
                new RecursiveUnwrapperStackItem([], null),
                new RecursiveUnwrapperStackItem([], 3),
                new RecursiveUnwrapperStackItem([], false),
            ],
            'expect' => '[NULL][3][false]',
        ];
    }

    /**
     * @dataProvider provCycle
     *
     * @param list<StackItem> $stack
     */
    public function testCycle(array $stack, string $expect): void
    {
        $rePath = preg_quote($expect, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested values at \\\$values{$rePath}\\.$/");

        (new RecursiveUnwrapperVisitor())->cycle([], $stack);
    }

    /**
     * @return iterable
     *
     * @psalm-return iterable<string, array{
     *      ctor: array,
     *      calls: non-empty-list<array{
     *          args: ArgsEnter,
     *          expect: mixed,
     *      }>,
     *      result: mixed
     *  }>
     */
    public static function provEnterLeave(): iterable
    {
        //
        // 01
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [],
            'calls' => [
                [
                    'args' => [
                        'node'  => [],
                        'stack' => [],
                    ],
                    'expect' => true,
                ],
            ],
            'result' => [],
        ];

        //
        // 02
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [],
            'calls' => [
                [
                    'args' => [
                        'node'  => new ExpectedValues(),
                        'stack' => [],
                    ],
                    'expect' => true,
                ],
            ],
            'result' => [self::UNIQUE_TAG => true],
        ];

        //
        // 03
        //

        $s03 = [
            new RecursiveUnwrapperStackItem(new ExpectedValues(), 'foo'),
            new RecursiveUnwrapperStackItem([], 'bar'),
        ];
        $v03 = new ExpectedValues();

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [],
            'calls' => [
                [
                    'args' => [
                        'node'  => $s03[0]->node(),
                        'stack' => [],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $s03[1]->node(),
                        'stack' => [$s03[0]],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $v03,
                        'stack' => $s03,
                    ],
                    'expect' => true,
                ],
            ],
            'result' => [
                'foo' => [
                    'bar' => [
                        self::UNIQUE_TAG => true,
                    ],
                ],
                self::UNIQUE_TAG => true,
            ],
        ];

        //
        // 04
        //
        $s04 = [
            new RecursiveUnwrapperStackItem(new ExpectedValues(), 'foo'),
            new RecursiveUnwrapperStackItem([], 'bar'),
        ];
        $v04 = new ActualValues();

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [],
            'calls' => [
                [
                    'args' => [
                        'node'  => $s04[0]->node(),
                        'stack' => [],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $s04[1]->node(),
                        'stack' => [$s04[0]],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $v04,
                        'stack' => $s04,
                    ],
                    'expect' => false,
                ],
            ],
            'result' => [
                'foo'            => [],
                self::UNIQUE_TAG => true,
            ],
        ];

        //
        // 05
        //
        $s05 = [
            new RecursiveUnwrapperStackItem(new ExpectedValues(), 'foo'),
            new RecursiveUnwrapperStackItem([], 'bar'),
        ];
        $v05 = new ExpectedValues();

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => [
                [
                    'args' => [
                        'node'  => $s05[0]->node(),
                        'stack' => [],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $s05[1]->node(),
                        'stack' => [$s05[0]],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $v05,
                        'stack' => $s05,
                    ],
                    'expect' => true,
                ],
            ],
            'result' => [
                'foo' => [
                    'bar' => [],
                ],
            ],
        ];

        //
        // 06
        //
        $s06 = [
            new RecursiveUnwrapperStackItem(new ExpectedValues(), 'foo'),
            new RecursiveUnwrapperStackItem([], 'bar'),
        ];
        $v06 = new ActualValues();

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => [
                [
                    'args' => [
                        'node'  => $s06[0]->node(),
                        'stack' => [],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $s06[1]->node(),
                        'stack' => [$s06[0]],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $v06,
                        'stack' => $s06,
                    ],
                    'expect' => false,
                ],
            ],
            'result' => [
                'foo' => [],
            ],
        ];
    }

    /**
     * @dataProvider provEnterLeave
     *
     * @param array                                       $ctor
     * @param list<array{args: ArgsEnter, expect: mixed}> $calls
     * @param mixed                                       $result
     *
     * @psalm-param non-empty-list<array{args: EnterArgsT, expect: mixed}> $calls
     */
    public function testEnterLeave(array $ctor, array $calls, $result): void
    {
        $visitor = new RecursiveUnwrapperVisitor(...$ctor);

        foreach ($calls as $call) {
            $args = array_values($call['args']);
            $this->assertSame($call['expect'], $visitor->enter(...$args));
        }

        foreach (array_reverse($calls) as $call) {
            $args = array_merge(array_values($call['args']), [$call['expect']]);
            $this->assertNull($visitor->leave(...$args));
        }

        $this->assertSame($result, $visitor->result());
    }

    /**
     * @return iterable
     *
     * @psalm-return iterable<string, array{
     *      calls: non-empty-list<array{
     *          args: ArgsVisit,
     *      }>,
     *      result: mixed
     *  }>
     */
    public static function provVisit(): iterable
    {
        //
        // 01
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'calls' => [
                [
                    'args' => [
                        'node'      => [],
                        'stack'     => [],
                        'iterating' => false,
                    ],
                ],
            ],
            'result' => [],
        ];

        //
        // 02
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'calls' => [
                [
                    'args' => [
                        'node'      => 'FOO',
                        'stack'     => [new RecursiveUnwrapperStackItem([], 'foo')],
                        'iterating' => false,
                    ],
                ],
                [
                    'args' => [
                        'node'      => 'BAR.GEZ',
                        'stack'     => [new RecursiveUnwrapperStackItem([], 'bar'), new RecursiveUnwrapperStackItem([], 'gez')],
                        'iterating' => false,
                    ],
                ],
            ],
            'result' => [
                'foo' => 'FOO',
                'bar' => ['gez' => 'BAR.GEZ'],
            ],
        ];

        //
        // 02
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'calls' => [
                [
                    'args' => [
                        'node'      => 'FOO',
                        'stack'     => [new RecursiveUnwrapperStackItem([], 'foo')],
                        'iterating' => false,
                    ],
                ],
                [
                    'args' => [
                        'node'      => 'FOO.BAR',
                        'stack'     => [new RecursiveUnwrapperStackItem([], 'foo'), new RecursiveUnwrapperStackItem([], 'bar')],
                        'iterating' => false,
                    ],
                ],
            ],
            'result' => [
                'foo' => 'FOO',
            ],
        ];
    }

    /**
     * @dataProvider provVisit
     *
     * @param array $calls
     * @param mixed $result
     *
     * @psalm-param non-empty-list<array{args: ArgsVisit}> $calls
     */
    public function testVisit(array $calls, $result): void
    {
        $visitor = new RecursiveUnwrapperVisitor();

        foreach ($calls as $call) {
            $args = array_values($call['args']);
            $this->assertNull($visitor->visit(...$args));
        }

        $this->assertSame($result, $visitor->result());
    }
}
// vim: syntax=php sw=4 ts=4 et:
