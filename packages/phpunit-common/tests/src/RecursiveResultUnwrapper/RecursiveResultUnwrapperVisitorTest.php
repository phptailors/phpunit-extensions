<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultUnwrapper;

use Tailors\PHPUnit\ArrayResult\DummyArrayResult;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ArrayResult\DummyUntaggedArrayResult;
use Tailors\PHPUnit\ArraySpec\DummyArraySelection;
use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\StaticTagInterface;
use Tailors\PHPUnit\InternalErrorException;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversal;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorInterface;
use Tailors\PHPUnit\ResultFactory\DummyArrayResultFactory;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapperVisitor
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type StackItem     = RecursiveResultUnwrapperStackItem
 * @psalm-type CtorArgs      = list{0: bool, 1?: bool}
 * @psalm-type EnterTestCall = array{args: array{node: array|\Traversable}, return: bool, next?: array-key}
 * @psalm-type CycleTestCall = array{args: array{node: array|\Traversable, stack: list<StackItem>}, return: mixed}
 * @psalm-type VisitTestCall = array{args: array{node: mixed}, key?: array-key}
 */
final class RecursiveResultUnwrapperVisitorTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsRecursiveVisitorInterface(): void
    {
        self::assertInstanceOf(RecursiveVisitorInterface::class, new RecursiveResultUnwrapperVisitor(false));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsStaticTagInterface(): void
    {
        self::assertInstanceOf(StaticTagInterface::class, new RecursiveResultUnwrapperVisitor(false));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInitialResult(): void
    {
        $visitor = new RecursiveResultUnwrapperVisitor(false);
        $this->assertNull($visitor->result());
    }

    /**
     * @psalm-return \Generator<non-falsy-string, array{
     *      ctor: CtorArgs,
     *      calls: non-empty-list<CycleTestCall>,
     *      result: mixed
     * }>
     */
    public static function provCycle(): iterable
    {
        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => [
                [
                    'args' => [
                        'node' => [],
                        'stack' => [],
                    ],
                    'return' => false,
                ],
                [
                    'args' => [
                        'node' => new DummyArrayResult(false, []),
                        'stack' => [],
                    ],
                    'return' => false,
                ],
                [
                    'args' => [
                        'node' => new DummyArrayResult(true, []),
                        'stack' => [],
                    ],
                    'return' => false,
                ],
            ],
            'result' => [],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true],
            'calls' => [
                [
                    'args' => [
                        'node' => [],
                        'stack' => [],
                    ],
                    'return' => false,
                ],
                [
                    'args' => [
                        'node' => new DummyArrayResult(false, []),
                        'stack' => [],
                    ],
                    'return' => false,
                ],
                [
                    'args' => [
                        'node' => new DummyArrayResult(true, []),
                        'stack' => [],
                    ],
                    'return' => false,
                ],
            ],
            'result' => [],
        ];
    }

    /**
     * @dataProvider provCycle
     *
     * @param mixed $result
     *
     * @psalm-param CtorArgs $ctor
     * @psalm-param non-empty-list<CycleTestCall> $calls
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCycle(array $ctor, array $calls, $result): void
    {
        $visitor = new RecursiveResultUnwrapperVisitor(...$ctor);

        foreach ($calls as $call) {
            $args = $call['args'];
            $this->assertSame($call['return'], $visitor->cycle($args['node'], $args['stack']));
        }
    }

    /**
     * @psalm-return \Generator<non-falsy-string, array{
     *      ctor: CtorArgs,
     *      calls: non-empty-list<EnterTestCall>,
     *      result: mixed
     *  }>
     */
    public static function provEnterLeave(): iterable
    {
        $tagk = RecursiveResultUnwrapperVisitor::tag();
        $tagd = (new DummyArrayResult(false))->tag();

        //
        // 01
        //

        $calls01 = [
            [
                'args' => [
                    'node' => [],
                ],
                'return' => true,
            ],
        ];
        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => $calls01,
            'result' => [],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true],
            'calls' => $calls01,
            'result' => [],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, false],
            'calls' => $calls01,
            'result' => [],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, true],
            'calls' => $calls01,
            'result' => [],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true, false],
            'calls' => $calls01,
            'result' => [],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true, true],
            'calls' => $calls01,
            'result' => [],
        ];

        //
        // 02
        //

        // __construct(false, ...) --> unwrapping only expected values
        $calls02e = [
            [
                'args' => [
                    'node' => new DummyArrayResult(false),
                ],
                'return' => true,
            ],
        ];

        // __construct(true, ...) --> unwrapping only actual values
        $calls02a = $calls02e;
        $calls02a[0]['return'] = false;

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => $calls02e,
            'result' => [$tagk => $tagd],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, true],
            'calls' => $calls02e,
            'result' => [$tagk => $tagd],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, false],
            'calls' => $calls02e,
            'result' => [],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true],
            'calls' => $calls02a,
            'result' => null,
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true, true],
            'calls' => $calls02a,
            'result' => null,
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true, false],
            'calls' => $calls02a,
            'result' => null,
        ];

        //
        // 03
        //

        // __construct(true, ...) --> unwrapping only actual values
        $calls03a = [
            [
                'args' => [
                    'node' => new DummyArrayResult(true),
                ],
                'return' => true,
            ],
        ];

        // __construct(false, ...) --> unwrapping only expected values
        $calls03e = $calls03a;
        $calls03e[0]['return'] = false;

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => $calls03e,
            'result' => null,
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, true],
            'calls' => $calls03e,
            'result' => null,
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, false],
            'calls' => $calls03e,
            'result' => null,
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true],
            'calls' => $calls03a,
            'result' => [$tagk => $tagd],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true, true],
            'calls' => $calls03a,
            'result' => [$tagk => $tagd],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true, false],
            'calls' => $calls03a,
            'result' => [],
        ];


        //
        // 04
        //

        $s04 = [new DummyArrayResult(false), [], new DummyArrayResult(false)];

        // __construct(false, ...) --> unwrapping only expected values
        $calls04e = [
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
                'return' => true,
            ],
        ];

        // __construct(true, ...) --> unwrapping only actual values
        $calls04a =  [
            [
                'args' => [
                    'node' => $s04[0],
                ],
                'return' => false,
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => $calls04e,
            'result' => [
                'foo' => [
                    'bar' => [
                        $tagk => $tagd,
                    ],
                ],
                $tagk => $tagd,
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, false],
            'calls' => $calls04e,
            'result' => [
                'foo' => [
                    'bar' => [
                    ],
                ],
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true],
            'calls' => $calls04a,
            'result' => null,
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true, false],
            'calls' => $calls04a,
            'result' => null,
        ];

        //
        // 05
        //
        $s05 = [new DummyArrayResult(false), [], new DummyArrayResult(true)];

        // __construct(false, ...) --> unwrapping only expected values
        $calls05e = [
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
                'return' => false,
            ],
        ];

        $calls05a = [
            [
                'args' => [
                    'node' => $s05[0],
                ],
                'return' => false,
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => $calls05e,
            'result' => [
                'foo' => [],
                $tagk => $tagd,
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, false],
            'calls' => $calls05e,
            'result' => [
                'foo' => [],
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true],
            'calls' => $calls05a,
            'result' => null,
        ];

        //
        // 06
        //

        $s06 = [new DummyArrayResult(false, [], 'tag1'), [], new DummyArrayResult(false, [], 'tag2')];

        // __construct(false, ...) --> unwrapping only expected values
        $calls06e = [
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
                'return' => true,
            ],
        ];

        $calls06a = [
            [
                'args' => [
                    'node' => $s06[0],
                ],
                'return' => false,
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => $calls06e,
            'result' => [
                'foo' => [
                    'bar' => [
                        $tagk => 'tag2',
                    ],
                ],
                $tagk => 'tag1',
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, false],
            'calls' => $calls06e,
            'result' => [
                'foo' => [
                    'bar' => [
                    ],
                ],
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true],
            'calls' => $calls06a,
            'result' => null,
        ];

        //
        // 07
        //

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => [
                [
                    'args' => [
                        'node' => new DummyUntaggedArrayResult(false, []),
                    ],
                    'return' => true,
                ]
            ],
            'result' => [
                $tagk => StaticRandomStrings::classTag(DummyUntaggedArrayResult::class),
            ],
        ];


    }

    /**
     * @dataProvider provEnterLeave
     *
     * @param mixed $result
     *
     * @psalm-param CtorArgs $ctor
     * @psalm-param non-empty-list<EnterTestCall> $calls
     *
     * @psalm-suppress MissingThrowsDocblock
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
     * @psalm-return \Generator<non-falsy-string, array{
     *      ctor: CtorArgs,
     *      root: array|\Traversable,
     *      iter: bool,
     *      calls: non-empty-list<VisitTestCall>,
     *      result: mixed
     *  }>
     */
    public static function provVisit(): iterable
    {
        $tagk = RecursiveResultUnwrapperVisitor::tag();
        $tagg = (new DummyArrayResult(false))->tag();

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
            'root'  => new DummyArrayResult(true),
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
     * @param array|\Traversable $root
     * @param mixed                 $result
     *
     * @psalm-param CtorArgs $ctor
     * @psalm-param non-empty-list<VisitTestCall> $calls
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testVisit(array $ctor, $root, bool $iter, array $calls, $result): void
    {
        $visitor = new RecursiveResultUnwrapperVisitor(...$ctor);
        $stack = [];

        $i = 0;
        $this->assertTrue($visitor->enter($root, $stack));
        foreach ($calls as $call) {
            $args = $call['args'];
            /** @psalm-var mixed */
            $node = $args['node'];

            if ($iter) {
                if (!array_key_exists('key', $call)) {
                    throw new InvalidArgumentException("Invalid data provided, missing \$calls[{$i}]['key'].");
                }
                array_push($stack, $visitor->makeStackItem($root, $call['key'], $stack));
            }

            $this->assertNull($visitor->visit($node, $stack, $iter));

            if ($iter) {
                $visitor->freeStackItem(array_pop($stack), $stack);
            }
            ++$i;
        }
        $visitor->leave($root, $stack, $iter);

        $this->assertSame($result, $visitor->result());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testVisitThrowsInvalidArgumentException(): void
    {
        $visitor = new RecursiveResultUnwrapperVisitor(false);

        $expected = preg_quote('an array or '.ResultInterface::class.' object', '/');
        $message = '/Argument [0-9] passed to [a-zA-Z\\\\]*RecursiveResultUnwrapperVisitor::set\(\) must be '.$expected.', string given/';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches($message);

        $visitor->visit('', [], false);
    }

    /**
     * @psalm-return \Generator<non-falsy-string, array{
     *      ctor: CtorArgs,
     *      array: array|\Traversable,
     *      result: mixed
     *  }>
     */
    public static function provUnwrapAcyclic(): iterable
    {
        $actualValues = [
            '[]' => new DummyArrayResult(true, []),
            '[foo => FOO]' => new DummyArrayResult(true, ['foo' => 'FOO']),
            '[baz => BAZ]' => new DummyArrayResult(true, ['baz' => 'BAZ']),
        ];

        $expectedValues = [
            '[]' => new DummyArrayResult(false, []),
            '[foo => FOO]' => new DummyArrayResult(false, ['foo' => 'FOO']),
            '[baz => BAZ]' => new DummyArrayResult(false, ['baz' => 'BAZ']),
        ];
        $arrayObject = ['[baz => BAZ]' => new \ArrayObject(['baz' => 'BAZ'])];

        $tagk = RecursiveResultUnwrapperVisitor::tag();
        $tagd = (new DummyArrayResult(false))->tag();

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
            'array' => new DummyArrayResult(false, []),
            'result' => [
                $tagk => $tagd,
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
            'array' => new DummyArrayResult(false, [], 'TAG'),
            'result' => [
                $tagk => 'TAG',
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [true],
            'array' => $expectedValues['[]'],
            'result' => $expectedValues['[]'],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [true],
            'array' => $expectedValues['[foo => FOO]'],
            'result' => $expectedValues['[foo => FOO]'],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
            'array' => $actualValues['[]'],
            'result' => $actualValues['[]'],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
            'array' => $actualValues['[foo => FOO]'],
            'result' => $actualValues['[foo => FOO]'],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
            'array' => new DummyArrayResult(false, [
                'foo' => 'FOO',
            ]),
            'result' => [
                'foo' => 'FOO',
                $tagk => $tagd,
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
            'array' => new DummyArrayResult(false, [
                'foo' => 'FOO',
                'bar' => [
                    'baz' => 'BAZ',
                    'qux' => 'QUX',
                ],
            ]),
            'result' => [
                'foo' => 'FOO',
                'bar' => [
                    'baz' => 'BAZ',
                    'qux' => 'QUX',
                ],
                $tagk => $tagd,
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
            'array' => new DummyArrayResult(false, [
                'foo' => 'FOO',
                'bar' => new DummyArrayResult(false, [
                    'baz' => 'BAZ',
                ]),
            ]),
            'result' => [
                'foo' => 'FOO',
                'bar' => [
                    'baz' => 'BAZ',
                    $tagk => $tagd,
                ],
                $tagk => $tagd,
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
            'array' => new DummyArrayResult(false, [
                'foo' => 'FOO',
                'bar' => new DummyArrayResult(false, [
                    'qux' => new DummyArrayResult(false, ['baz' => 'BAZ']),
                    new DummyArrayResult(false, ['fred' => 'FRED']),
                ]),
            ]),
            'result' => [
                'foo' => 'FOO',
                'bar' => [
                    'qux' => [
                        'baz' => 'BAZ',
                        $tagk => $tagd,
                    ],
                    0 => [
                        'fred' => 'FRED',
                        $tagk  => $tagd,
                    ],
                    $tagk => $tagd,
                ],
                $tagk => $tagd,
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
            'array' => new DummyArrayResult(false, [
                'foo' => 'FOO',
                'bar' => $actualValues['[baz => BAZ]'],
            ]),
            'result' => [
                'foo' => 'FOO',
                'bar' => $actualValues['[baz => BAZ]'],
                $tagk => $tagd,
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [true],
            'array' => new DummyArrayResult(true, [
                'foo' => 'FOO',
                'bar' => $expectedValues['[baz => BAZ]'],
            ]),
            'result' => [
                'foo' => 'FOO',
                'bar' => $expectedValues['[baz => BAZ]'],
                $tagk => $tagd,
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false],
            'array' => new DummyArrayResult(false, [
                'foo' => 'FOO',
                'bar' => $arrayObject['[baz => BAZ]'],
            ]),
            'result' => [
                'foo' => 'FOO',
                'bar' =>  $arrayObject['[baz => BAZ]'],
                $tagk => $tagd,
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false, false], // no tagging
            'array' => new DummyArrayResult(false, [
                'foo' => 'FOO',
            ]),
            'result' => [
                'foo' => 'FOO',
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false, false], // no tagging
            'array' => new DummyArrayResult(false, [
                'foo' => new DummyArrayResult(false, []),
            ]),
            'result' => [
                'foo' => [],
            ],
        ];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [false, false], // no tagging
            'array' => new DummyArrayResult(false, [
                'foo' => 'FOO',
                'bar' => new DummyArrayResult(false, [
                    'baz' => 'BAZ',
                ]),
            ]),
            'result' => [
                'foo' => 'FOO',
                'bar' => [
                    'baz' => 'BAZ',
                ],
            ],
        ];
    }

    /**
     * @dataProvider provUnwrapAcyclic
     *
     * @param array|\Traversable $array
     * @param mixed $result
     *
     * @psalm-param CtorArgs $ctor
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testUnwrapAcyclic(array $ctor, $array, $result): void
    {
        $traversal = new RecursiveTraversal();
        $visitor = new RecursiveResultUnwrapperVisitor(...$ctor);

        $traversal->walk($array, $visitor);

        self::assertSame($result, $visitor->result());
    }

    /**
     * @psalm-return \Generator<non-falsy-string, array{
     *      ctor: CtorArgs,
     *      array: array|\Traversable,
     *      result: mixed
     *  }>
     *
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArrayAssignment
     * @psalm-suppress MixedArrayAccess
     */
    public static function provUnwrapCyclic(): iterable
    {
        $tagk = RecursiveResultUnwrapperVisitor::tag();
        $tagd = (new DummyArrayResult(false))->tag();

        //
        // 01
        //
        $v01 = new DummyArrayResult(true, [
            'foo' => [],
        ]);
        $v01['foo']['bar'] = $v01;

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [true],
            'array' => $v01,
            'result' => [
                'foo' => [
                    'bar' => $v01,
                ],
                $tagk => $tagd,
            ],
        ];

        //
        // 02
        //

        $v02 = new DummyArrayResult(true, [
            'foo' => [
                'bar' => [],
            ],
        ]);
        $v02['foo']['bar']['baz'] = $v02;

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [true],
            'array' => $v02,
            'result' => [
                'foo' => [
                    'bar' => [
                        'baz' => $v02,
                    ],
                ],
                $tagk => $tagd,
            ],
        ];

        //
        // 03
        //

        $v03 = new DummyArrayResult(true, [
            'foo' => [
                'bar' => new DummyArrayResult(true, [
                    'baz' => 'BAZ',
                ]),
            ],
        ]);
        $v03['foo']['bar']['qux'] = $v03['foo']['bar'];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [true],
            'array' => $v03,
            'result' => [
                'foo' => [
                    'bar' => [
                        'baz' => 'BAZ',
                        'qux' => $v03['foo']['bar'],
                        $tagk => $tagd,
                    ],
                ],
                $tagk => $tagd,
            ],
        ];

        //
        // 04
        //

        $v04 = new DummyArrayResult(true, [
            'foo' => [
                'bar' => new DummyArrayResult(true, []),
                'baz' => new DummyArrayResult(true, []),
            ],
        ]);
        $v04['foo']['bar']['qux'] = $v04['foo']['baz'];
        $v04['foo']['baz']['gez'] = $v04['foo']['bar'];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [true],
            'array' => $v04,
            'result' => [
                'foo' => [
                    'bar' => [
                        'qux' => [
                            'gez' => $v04['foo']['bar'],
                            $tagk => $tagd,
                        ],
                        $tagk => $tagd,
                    ],
                    'baz' => [
                        'gez' => [
                            'qux' => $v04['foo']['baz'],
                            $tagk => $tagd,
                        ],
                        $tagk => $tagd,
                    ],
                ],
                $tagk => $tagd,
            ],
        ];

        //
        // 05
        //

        $v05 = new DummyArrayResult(true, [
            'foo' => [
                'bar' => [],
            ],
        ]);
        $v05['foo']['baz'] = &$v05['foo'];

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [true],
            'array' => $v05,
            'result' => [
                'foo' => [
                    'bar' => [],
                    'baz' => &$v05['foo'],
                ],
                $tagk => $tagd,
            ],
        ];

        //
        // 06
        //

        $v06 = [
            'foo' => new DummyArrayResult(true, [
                'bar' => [],
            ]),
        ];
        $v06['foo']['bar']['baz'] = &$v06;

        yield 'RecursiveResultUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor' => [true],
            'array' => $v06['foo'],
            'result' => [
                'bar' => [
                    'baz' => [
                        'foo' => $v06['foo'],
                    ],
                ],
                $tagk => $tagd,
            ],
        ];
    }

    /**
     * @dataProvider provUnwrapCyclic
     *
     * @param array|\Traversable $array
     * @param mixed $result
     *
     * @psalm-param CtorArgs $ctor
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testUnwrapCyclic(array $ctor, $array, $result): void
    {
        $traversal = new RecursiveTraversal();
        $visitor = new RecursiveResultUnwrapperVisitor(...$ctor);

        $traversal->walk($array, $visitor);

        self::assertSame($result, $visitor->result());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLeaveThrowsInternalErrorException(): void
    {
        $visitor = new RecursiveResultUnwrapperVisitor(false);

        $stack = [];
        $node = new DummyArrayResult(false, [$visitor::tag() => 'ANYTHING']);

        $iterating = $visitor->enter($node, $stack);
        $this->assertTrue($iterating);
        array_push($stack, $visitor->makeStackItem($node, $visitor::tag(), $stack));

        $visitor->visit($node[$visitor::tag()], $stack, $iterating);

        $visitor->freeStackItem(array_pop($stack), $stack);


        $key = preg_quote(var_export($visitor::tag(), true), '/');
        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessageMatches("/^Failed to set \\\$array\\[{$key}\\]: key already exists. Please re-run your tests\.$/");

        $visitor->leave($node, $stack, $iterating);
    }
}
// vim: syntax=php sw=4 ts=4 et:
