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
use Tailors\PHPUnit\Common\StaticTagInterface;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorInterface;
use Tailors\PHPUnit\Values\DummyValues;
use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Recursive\RecursiveResultUnwrapperVisitor
 * @covers \Tailors\PHPUnit\Values\AbstractValues
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type StackItem     = RecursiveResultUnwrapperStackItem
 * @psalm-type EnterTestCall = array{args: array{node: array|ValuesInterface}, return: mixed, next?: array-key}
 * @psalm-type VisitTestCall = array{args: array{node: mixed}, key?: array-key}
 */
final class RecursiveResultUnwrapperVisitorTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function testImplementsRecursiveVisitorInterface(): void
    {
        self::assertInstanceOf(RecursiveVisitorInterface::class, new RecursiveResultUnwrapperVisitor(false));
    }

    public function testImplementsStaticTagInterface(): void
    {
        self::assertInstanceOf(StaticTagInterface::class, new RecursiveResultUnwrapperVisitor(false));
    }

    public function testInitialResult(): void
    {
        $visitor = new RecursiveResultUnwrapperVisitor(false);
        $this->assertSame([], $visitor->result());
    }

    /**
     * @psalm-return iterable<string, array{stack: list<StackItem>, expect: string}>
     */
    public static function provCycle(): iterable
    {
        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
            'stack'  => [],
            'expect' => '',
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
            'stack' => [
                new RecursiveResultUnwrapperStackItem([], 'foo', []),
                new RecursiveResultUnwrapperStackItem([], 3, []),
                new RecursiveResultUnwrapperStackItem([], 'bar', []),
            ],
            'expect' => "['foo'][3]['bar']",
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
            'stack' => [
                new RecursiveResultUnwrapperStackItem([], null, []),
                new RecursiveResultUnwrapperStackItem([], 3, []),
                new RecursiveResultUnwrapperStackItem([], false, []),
            ],
            'expect' => '[NULL][3][false]',
        ];
    }

    /**
     * @dataProvider provCycle
     *
     * @psalm-param list<StackItem> $stack
     */
    public function testCycle(array $ctor, array $stack, string $expect): void
    {
        $rePath = preg_quote($expect, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested values at \\\$values{$rePath}\\.$/");

        (new RecursiveResultUnwrapperVisitor(...$ctor))->cycle([], $stack);
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctor: array,
     *      calls: non-empty-list<EnterTestCall>,
     *      result: mixed
     *  }>
     */
    public static function provEnterLeave(): iterable
    {
        $tagk = RecursiveResultUnwrapperVisitor::tag();
        $tagg = (new DummyValues(false))->tag();
        $tagd = (new DummyValues(false))->tag();

        //
        // 01
        //

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
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

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => [
                [
                    'args' => [
                        'node' => new DummyValues(false),
                    ],
                    'return' => true,
                ],
            ],
            'result' => [$tagk => $tagg],
        ];

        //
        // 03
        //

        $s03 = [new DummyValues(false), [], new DummyValues(false)];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
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
                        $tagk => $tagg,
                    ],
                ],
                $tagk => $tagg,
            ],
        ];

        //
        // 04
        //
        $s04 = [new DummyValues(false), [], new DummyValues(true)];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
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
                'foo' => [],
                $tagk => $tagg,
            ],
        ];

        //
        // 05
        //
        $s05 = [new DummyValues(false), [], new DummyValues(false)];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, false],
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
        $s06 = [new DummyValues(false), [], new DummyValues(true)];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, false],
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

        //
        // 07
        //
        $s07 = [new DummyValues(false), [], new DummyValues(false)];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => [
                [
                    'args' => [
                        'node' => $s07[0],
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args' => [
                        'node' => $s07[1],
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args' => [
                        'node' => $s07[2],
                    ],
                    'return' => true,
                ],
            ],
            'result' => [
                'foo' => [
                    'bar' => [
                        $tagk => $tagd,
                    ],
                ],
                $tagk => $tagg,
            ],
        ];

        //
        // 08
        //
        $s08 = [new DummyValues(false), [], new DummyValues(true)];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, false],
            'calls' => [
                [
                    'args' => [
                        'node' => $s08[0],
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args' => [
                        'node' => $s08[1],
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args' => [
                        'node' => $s08[2],
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
     * @dataProvider provEnterLeave
     *
     * @param mixed $result
     *
     * @psalm-param non-empty-list<EnterTestCall> $calls
     */
    public function testEnterLeave(array $ctor, array $calls, $result): void
    {
        $visitor = new RecursiveResultUnwrapperVisitor(...$ctor);
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
     * @psalm-return iterable<string, array{
     *      iter: bool,
     *      calls: non-empty-list<VisitTestCall>,
     *      result: mixed
     *  }>
     */
    public static function provVisit(): iterable
    {
        $tagk = RecursiveResultUnwrapperVisitor::tag();
        $tagg = (new DummyValues(false))->tag();

        //
        // 01
        //

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
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

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
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

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [true],
            'root'  => new DummyValues(true),
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
                'foo' => 'FOO',
                'bar' => ['gez' => 'GEZ'],
                $tagk => $tagg,
            ],
        ];
    }

    /**
     * @dataProvider provVisit
     *
     * @param array|ValuesInterface $root
     * @param mixed                 $result
     *
     * @psalm-param non-empty-list<VisitTestCall> $calls
     */
    public function testVisit(array $ctor, $root, bool $iter, array $calls, $result): void
    {
        $visitor = new RecursiveResultUnwrapperVisitor(...$ctor);
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

    public function testVisitThrowsInvalidArgumentException(): void
    {
        $visitor = new RecursiveResultUnwrapperVisitor(false);

        $message = '/Argument 2 passed to [a-zA-Z\\\\]*RecursiveResultUnwrapperVisitor::set\(\) must be an array, string given/';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches($message);

        $visitor->visit('', [], false);
    }
}
// vim: syntax=php sw=4 ts=4 et:
