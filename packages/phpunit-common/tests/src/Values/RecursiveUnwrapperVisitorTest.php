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
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\CircularDependencyException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type StackItem = RecursiveUnwrapperStackItem
 * @psalm-type EnterTestCall = array{args: array{node: array|ValuesInterface}, return: mixed, next?: array-key}
 * @psalm-type VisitTestCall = array{args: array{node: mixed}, key?: array-key}
 */
#[CoversClass(RecursiveUnwrapperVisitor::class)]
#[Small]
final class RecursiveUnwrapperVisitorTest extends TestCase
{
    public const string UNIQUE_TAG = RecursiveUnwrapperVisitor::UNIQUE_TAG;

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
                new RecursiveUnwrapperStackItem([], 'foo', []),
                new RecursiveUnwrapperStackItem([], 3, []),
                new RecursiveUnwrapperStackItem([], 'bar', []),
            ],
            'expect' => "['foo'][3]['bar']",
        ];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'stack' => [
                new RecursiveUnwrapperStackItem([], null, []),
                new RecursiveUnwrapperStackItem([], 3, []),
                new RecursiveUnwrapperStackItem([], false, []),
            ],
            'expect' => '[NULL][3][false]',
        ];
    }

    /**
     * @param list<StackItem> $stack
     */
    #[DataProvider('provCycle')]
    public function testCycle(array $stack, string $expect): void
    {
        $rePath = preg_quote($expect, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested values at \\\$values{$rePath}\\.$/");

        new RecursiveUnwrapperVisitor()->cycle([], $stack);
    }

    /**
     * @return iterable
     *
     * @psalm-return iterable<string, array{
     *      ctor: array,
     *      calls: non-empty-list<EnterTestCall>,
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
                        'node' => [],
                    ],
                    'return' => true,
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
                        'node' => new ExpectedValues(),
                    ],
                    'return' => true,
                ],
            ],
            'result' => [self::UNIQUE_TAG => true],
        ];

        //
        // 03
        //

        $s03 = [new ExpectedValues(), [], new ExpectedValues()];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [],
            'calls' => [
                [
                    'args' => [
                        'node' => $s03[0],
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args' => [
                        'node' => $s03[1],
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args' => [
                        'node' => $s03[2],
                    ],
                    'return' => true,
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
        $s04 = [new ExpectedValues(), [], new ActualValues()];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [],
            'calls' => [
                [
                    'args' => [
                        'node' => $s04[0],
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args' => [
                        'node' => $s04[1],
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args' => [
                        'node' => $s04[2],
                    ],
                    'return' => false,
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
        $s05 = [new ExpectedValues(), [], new ExpectedValues()];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => [
                [
                    'args' => [
                        'node' => $s05[0],
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args' => [
                        'node' => $s05[1],
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args' => [
                        'node' => $s05[2],
                    ],
                    'return' => true,
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
        $s06 = [new ExpectedValues(), [], new ActualValues()];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => [
                [
                    'args' => [
                        'node' => $s06[0],
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args' => [
                        'node' => $s06[1],
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args' => [
                        'node' => $s06[2],
                    ],
                    'return' => false,
                ],
            ],
            'result' => [
                'foo' => [],
            ],
        ];
    }

    /**
     * @param array       $ctor
     * @param list<array> $calls
     *
     * @psalm-param non-empty-list<EnterTestCall> $calls
     */
    #[DataProvider('provEnterLeave')]
    public function testEnterLeave(array $ctor, array $calls, mixed $result): void
    {
        $visitor = new RecursiveUnwrapperVisitor(...$ctor);
        $stack = [];

        foreach ($calls as $call) {
            $args = $call['args'];
            $node = $args['node'];
            $this->assertSame($call['return'], $visitor->enter($node, $stack));
            if ($call['return'] && array_key_exists('next', $call)) {
                array_push($stack, $visitor->makeStackItem($node, $call['next'], $stack));
            }
        }

        foreach (array_reverse($calls) as $call) {
            $args = $call['args'];
            $node = $args['node'];
            if ($call['return'] && array_key_exists('next', $call)) {
                $visitor->freeStackItem(array_pop($stack), $stack);
            }
            $this->assertNull($visitor->leave($node, $stack, $call['return']));
        }

        $this->assertSame($result, $visitor->result());
    }

    /**
     * @return iterable
     *
     * @psalm-return iterable<string, array{
     *      iter: bool,
     *      calls: non-empty-list<VisitTestCall>,
     *      result: mixed
     *  }>
     */
    public static function provVisit(): iterable
    {
        //
        // 01
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'root'  => [],
            'iter'  => false,
            'calls' => [
                [
                    'args' => [
                        'node' => [],
                    ],
                ],
            ],
            'result' => [],
        ];

        //
        // 02
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'root'  => [],
            'iter'  => true,
            'calls' => [
                [
                    'key'  => 'foo',
                    'args' => [
                        'node' => 'FOO',
                    ],
                ],
                [
                    'key'  => 'bar',
                    'args' => [
                        'node' => 'BAR',
                    ],
                ],
            ],
            'result' => [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ],
        ];

        //
        // 02
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'root'  => new ActualValues(),
            'iter'  => true,
            'calls' => [
                [
                    'key'  => 'foo',
                    'args' => [
                        'node' => 'FOO',
                    ],
                ],
                [
                    'key'  => 'bar',
                    'args' => [
                        'node' => ['gez' => 'GEZ'],
                    ],
                ],
            ],
            'result' => [
                'foo'            => 'FOO',
                'bar'            => ['gez' => 'GEZ'],
                self::UNIQUE_TAG => true,
            ],
        ];
    }

    /**
     * @param array $calls
     *
     * @psalm-param non-empty-list<VisitTestCall> $calls
     */
    #[DataProvider('provVisit')]
    public function testVisit(array|ValuesInterface $root, bool $iter, array $calls, mixed $result): void
    {
        $visitor = new RecursiveUnwrapperVisitor();
        $stack = [];

        $this->assertTrue($visitor->enter($root, $stack));
        foreach ($calls as $call) {
            $args = $call['args'];
            $node = $args['node'];

            if ($iter) {
                array_push($stack, $visitor->makeStackItem($root, $call['key'], $stack));
            }

            $this->assertNull($visitor->visit($node, $stack, $iter));

            if ($iter) {
                $visitor->freeStackItem(array_pop($stack), $stack);
            }
        }
        $visitor->leave($root, $stack, $iter);

        $this->assertSame($result, $visitor->result());
    }
}
// vim: syntax=php sw=4 ts=4 et:
