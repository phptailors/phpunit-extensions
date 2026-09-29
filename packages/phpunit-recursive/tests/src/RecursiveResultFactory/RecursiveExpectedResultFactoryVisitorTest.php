<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ArrayResult\DummyArrayResult;
use Tailors\PHPUnit\ArrayResult\ExpectedArrayResult;
use Tailors\PHPUnit\ArraySpec\DummyArraySelection;
use Tailors\PHPUnit\ArraySpec\DummyArraySpec;
use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\InternalErrorException;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapper;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversal;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorInterface;
use Tailors\PHPUnit\Result\ResultInterface;
use Tailors\PHPUnit\ResultFactory\DummyArrayResultFactory;
use Tailors\PHPUnit\ResultFactory\DummyResultFactory;
use Tailors\PHPUnit\Values\ValuesInterface;
use Tailors\PHPUnit\ValueSelector\DummyValueSelector;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveResultFactory\RecursiveExpectedResultFactoryVisitor
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike     = iterable<array-key, mixed>
 * @psalm-type StackItem     = RecursiveExpectedResultFactoryStackItem
 * @psalm-type CtorArgs      = list{}
 * @psalm-type EnterTestCall = array{args: array{node: array|ValuesInterface}, return: bool, next?: mixed}
 * @psalm-type VisitTestCall = array{args: array{node: mixed}, key?:array-key}
 */
final class RecursiveExpectedResultFactoryVisitorTest extends TestCase
{
    public function testImplementsRecursiveExpectedResultFactoryVisitorInterface(): void
    {
        self::assertInstanceOf(RecursiveExpectedResultFactoryVisitorInterface::class, new RecursiveExpectedResultFactoryVisitor());
    }

    public function testImplementsRecursiveVisitorInterface(): void
    {
        self::assertInstanceOf(RecursiveVisitorInterface::class, new RecursiveExpectedResultFactoryVisitor());
    }

    public function testInitialResult(): void
    {
        $visitor = new RecursiveExpectedResultFactoryVisitor();
        $this->assertNull($visitor->result());
    }

    /**
     * @psalm-return \Generator<non-falsy-string, array{path: list<array-key>, expect: string}>
     */
    public static function provCycle(): iterable
    {
        //
        // 01
        //

        yield basename(__FILE__).':'.__LINE__ => [
            'stack'  => [],
            'expect' => '',
        ];

        //
        // 02
        //

        $s02 = array_map(function ($key) {
            return new RecursiveExpectedResultFactoryStackItem([], $key, []);
        }, ['foo', 3, 'bar']);

        yield basename(__FILE__).':'.__LINE__ => [
            'stack'  => $s02,
            'expect' => "['foo'][3]['bar']",
        ];

        //
        // 03
        //

        $s03 = array_map(function ($key) {
            return new RecursiveExpectedResultFactoryStackItem([], $key, []);
        }, [null, 3, false]);

        yield basename(__FILE__).':'.__LINE__ => [
            'stack'  => $s03,
            'expect' => '[NULL][3][false]',
        ];
    }

    /**
     * @dataProvider provCycle
     *
     * @psalm-param list<StackItem> $stack
     */
    public function testCycle(array $stack, string $expect): void
    {
        $rePath = preg_quote($expect, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested array at \\\$array{$rePath}\\.$/");

        (new RecursiveExpectedResultFactoryVisitor())->cycle([], $stack);
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctor: CtorArgs,
     *      calls: non-empty-list<EnterTestCall>,
     *      result: mixed
     *  }>
     */
    public static function provEnterLeave(): iterable
    {
        //
        // 01
        //

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'calls'  => [
                [
                    'args'   => [
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
        $e02 = new DummyArraySelection(
            new DummyArrayResultFactory(),
            new DummyValueSelector(false),
            ['foo' => 'FOO']
        );

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'calls'  => [
                [
                    'args'   => [
                        'node' => $e02,
                    ],
                    'return' => true,
                ],
            ],
            'result' => new DummyArrayResult(false, ['foo' => 'FOO']),
        ];

        //
        // 03
        //

        $e03 = new DummyArraySelection(
            new DummyArrayResultFactory(),
            new DummyValueSelector(true),
            ['foo' => 'FOO']
        );

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'calls'  => [
                [
                    'args'   => [
                        'node' => $e03,
                    ],
                    'return' => true,
                ],
            ],
            'result' => new DummyArrayResult(false, ['foo' => 'FOO']),
        ];

        //
        // 04
        //

        $e04 = new DummyArraySpec(
            new DummyArrayResultFactory(),
            ['foo' => 'FOO']
        );

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'calls'  => [
                [
                    'args'   => [
                        'node' => $e04,
                    ],
                    'return' => true,
                ],
            ],
            'result' => new DummyArrayResult(false, ['foo' => 'FOO']),
        ];

        //
        // 05
        //

        $f05 = new DummyArrayResultFactory();
        $s05 = new DummyValueSelector(false);

        $e05 = new DummyArraySelection($f05, $s05, [
            'foo' => new DummyArraySelection($f05, $s05, [
                'bar' => ['BAR'],
            ]),
        ]);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'calls'  => [
                [
                    'args'   => [
                        'node' => $e05,
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args'   => [
                        'node' => $e05['foo'],
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args'   => [
                        'node' => $e05['foo']['bar'],
                    ],
                    'return' => true,
                    'next'   => 0,
                ],
            ],
            'result' => new DummyArrayResult(false, [
                'foo' => new DummyArrayResult(false, [
                    'bar' => ['BAR'],
                ]),
            ]),
        ];

        //
        // 06
        //

        $f06 = new DummyArrayResultFactory();
        $s06 = new DummyValueSelector(false);

        $e06 = new DummyArraySelection($f06, $s06, [
            'foo' => [
                'bar' => new DummyArraySelection($f06, $s06, [
                    'BAR',
                ]),
            ],
        ]);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'calls'  => [
                [
                    'args'   => [
                        'node' => $e06,
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args'   => [
                        'node' => $e06['foo'],
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args'   => [
                        'node' => $e06['foo']['bar'],
                    ],
                    'return' => true,
                    'next'   => 0,
                ],
            ],
            'result' => new DummyArrayResult(false, [
                'foo' => [
                    'bar' => new DummyArrayResult(false, ['BAR']),
                ],
            ]),
        ];

        //
        // 07
        //

        $f07 = new DummyArrayResultFactory();
        $s07 = new DummyValueSelector(false);

        $e07 = new DummyArraySelection($f07, $s07, [
            'foo' => [
                'bar' => [
                    'baz' => [],
                ],
            ],
        ]);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'calls'  => [
                [
                    'args'   => [
                        'node' => $e07,
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args'   => [
                        'node' => $e07['foo'],
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args'   => [
                        'node' => $e07['foo']['bar'],
                    ],
                    'return' => true,
                    'next'   => 'baz',
                ],
                [
                    'args'   => [
                        'node' => $e07['foo']['bar']['baz'],
                    ],
                    'return' => true,
                ],
            ],
            'result' => new DummyArrayResult(false, [
                'foo' => [
                    'bar' => [
                        'baz' => [],
                    ],
                ],
            ]),
        ];

        //
        // 08
        //

        $e08 = new DummyArraySelection(
            new DummyResultFactory(false), // does not support anything (including iterable)
            new DummyValueSelector(true),  // select everything from array (but it's unimportant here)
            []
        );

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'calls'  => [
                [
                    'args'   => [
                        'node' => $e08,
                    ],
                    'return' => false,
                ],
            ],
            'result' => null,
        ];

        //
        // 09
        //

        $e09 = new DummyArraySelection(
            new DummyResultFactory(true), // supports everything, but returns non-array result
            new DummyValueSelector(true), // select everything from array (but it's unimportant here)
            []
        );

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'calls'  => [
                [
                    'args'   => [
                        'node' => $e09,
                    ],
                    'return' => false,
                ],
            ],
            'result' => null,
        ];
    }

    /**
     * @dataProvider provEnterLeave
     *
     * @param mixed $result
     *
     * @psalm-param CtorArgs                      $ctor
     * @psalm-param non-empty-list<EnterTestCall> $calls
     */
    public function testEnterLeave(array $ctor, array $calls, $result): void
    {
        $visitor = new RecursiveExpectedResultFactoryVisitor(...$ctor);
        $stack = [];

        $visitor->begin();

        foreach ($calls as $call) {
            $args = $call['args'];
            $this->assertSame($call['return'], $visitor->enter($args['node'], $stack));
            if (array_key_exists('next', $call)) {
                array_push($stack, $visitor->makeStackItem($args['node'], $call['next'], $stack));
            }
        }

        foreach (array_reverse($calls) as $call) {
            $args = $call['args'];
            if (array_key_exists('next', $call)) {
                $visitor->freeStackItem(array_pop($stack), $stack);
            }
            $this->assertNull($visitor->leave($args['node'], $stack, $call['return']));
        }

        $visitor->end();

        $expect = $result;
        $actual = $visitor->result();

        $resultUnwrapper = RecursiveResultUnwrapper::create();

        if ($expect instanceof ResultInterface) {
            $expect = $resultUnwrapper->unwrap(false, $expect);
        }

        if ($actual instanceof ResultInterface) {
            $actual = $resultUnwrapper->unwrap(false, $actual);
        }

        $this->assertSame($expect, $actual);
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctor: CtorArgs,
     *      calls: non-empty-list<VisitTestCall>,
     *      result: mixed
     *  }>
     */
    public static function provVisit(): iterable
    {
        //
        // 01
        //

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'enter'  => null,
            'calls'  => [
                [
                    'args' => [
                        'node' => null,
                        'iter' => false,
                    ],
                ],
            ],
            'result' => null,
        ];

        //
        // 02
        //

        $f02 = new DummyArrayResultFactory();
        $s02 = new DummyValueSelector(false);

        $e02 = new DummyArraySelection($f02, $s02, [
            'foo' => 'FOO',
            'bar' => 'BAR',
            'gez' => 'GEZ',
        ]);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'enter'  => [
                'args'   => [
                    'node' => $e02,
                ],
                'return' => true,
            ],
            'calls'  => [
                [
                    'args' => [
                        'node' => $e02['foo'],
                    ],
                    'key'  => 'foo',
                ],
                [
                    'args' => [
                        'node' => $e02['bar'],
                    ],
                    'key'  => 'bar',
                ],
                [
                    'args' => [
                        'node' => $e02['gez'],
                    ],
                    'key'  => 'gez',
                ],
            ],
            'result' => new DummyArrayResult(false, [
                'foo' => 'FOO',
                'bar' => 'BAR',
                'gez' => 'GEZ',
            ]),
        ];

        //
        // 03
        //

        $e03 = [];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'enter'  => [
                'args'   => [
                    'node' => [
                        'foo' => 'FOO',
                        'bar' => 'BAR',
                        'baz' => 'BAZ',
                    ],
                ],
                'return' => true,
            ],
            'calls'  => [
                [
                    'args' => [
                        'node' => null,
                    ],
                    'key'  => 'foo',
                ],
                [
                    'args' => [
                        'node' => null,
                    ],
                    'key'  => 'bar',
                ],
                [
                    'args' => [
                        'node' => null,
                    ],
                    'key'  => 'gez',
                ],
            ],
            'result' => [
                'foo' => null,
                'bar' => null,
                'baz' => 'BAZ',
                'gez' => null,
            ],
        ];
    }

    /**
     * @dataProvider provVisit
     *
     * @param mixed $result
     *
     * @psalm-param CtorArgs                      $ctor
     * @psalm-param ?EnterTestCall                $enter
     * @psalm-param non-empty-list<VisitTestCall> $calls
     */
    public function testVisit(array $ctor, ?array $enter, array $calls, $result): void
    {
        $visitor = new RecursiveExpectedResultFactoryVisitor(...$ctor);
        $stack = [];

        $visitor->begin();

        if (null !== $enter) {
            $args = $enter['args'];
            $this->assertSame($enter['return'], $visitor->enter($args['node'], $stack));
        }

        $iter = null === $enter ? false : $enter['return'];

        foreach ($calls as $call) {
            $args = $call['args'];
            if (array_key_exists('key', $call)) {
                array_push($stack, $visitor->makeStackItem($enter['args']['node'], $call['key'], $stack));
            }
            $this->assertNull($visitor->visit($args['node'], $stack, $iter));
            if (array_key_exists('key', $call)) {
                $visitor->freeStackItem(array_pop($stack), $stack);
            }
        }

        if (null !== $enter) {
            $args = $enter['args'];
            $this->assertNull($visitor->leave($args['node'], $stack, $enter['return']));
        }

        $visitor->end();

        $expect = $result;
        $actual = $visitor->result();

        $resultUnwrapper = RecursiveResultUnwrapper::create();

        if ($expect instanceof ResultInterface) {
            $expect = $resultUnwrapper->unwrap(false, $expect);
        }

        if ($actual instanceof ResultInterface) {
            $actual = $resultUnwrapper->unwrap(false, $actual);
        }

        $this->assertSame($expect, $actual);
    }

    /**
     * @psalm-return \Generator<non-falsy-string, array{
     *      ctor: CtorArgs,
     *      array: ArrayLike,
     *      result: mixed
     *  }>
     */
    public static function provWithRecursiveTraversal(): iterable
    {
        //
        // 01
        //

        $f01 = new DummyArrayResultFactory();
        $s01 = new DummyValueSelector(false);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'array'  => new DummyArraySelection($f01, $s01, []),
            'result' => new DummyArrayResult(false, []),
        ];

        //
        // 03
        //

        $f03 = new DummyArrayResultFactory();
        $s03 = new DummyValueSelector(false);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'array'  => new DummyArraySelection($f03, $s03, [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ]),
            'result' => new DummyArrayResult(false, [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ]),
        ];

        //
        // 04
        //

        $f04 = new DummyArrayResultFactory();
        $s04 = new DummyValueSelector(false);
        $b04 = new ExpectedArrayResult([]);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'array'  => new DummyArraySelection($f04, $s04, [
                'foo' => ['FOO'],
                'bar' => $b04,
            ]),
            'result' => new DummyArrayResult(false, [
                'foo' => ['FOO'],
                'bar' => $b04,
            ]),
        ];

        //
        // 05
        //

        $f05 = new DummyArrayResultFactory();
        $s05 = new DummyValueSelector(false);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'array'  => new DummyArraySelection($f05, $s05, [
                'foo' => [
                    'qux' => new DummyArraySelection($f05, $s05, [
                        'cez' => 'CEZ',
                    ]),
                ],
                'bar' => new DummyArraySelection($f05, $s05, ['baz' => 'BAZ']),
            ]),
            'result' => new DummyArrayResult(false, [
                'foo' => [
                    'qux' => new DummyArrayResult(false, [
                        'cez' => 'CEZ',
                    ]),
                ],
                'bar' => new DummyArrayResult(false, ['baz' => 'BAZ']),
            ]),
        ];

        //
        // 06
        //

        $f06 = new DummyResultFactory(false); // does not support anything (including iterable)
        $s06 = new DummyValueSelector(true);  // select everything from array (but it's unimportant here)
        $a06 = new DummyArraySelection($f06, $s06, [
            'foo' => [
                'qux' => new DummyArraySelection($f06, $s06, [
                    'cez' => 'CEZ',
                ]),
            ],
            'bar' => new DummyArraySelection($f06, $s06, ['baz' => 'BAZ']),
        ]);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'array'  => $a06,
            'result' => $a06,
        ];

        //
        // 07
        //

        $f07 = new DummyResultFactory(false); // does not support anything (including iterable)
        $s07 = new DummyValueSelector(true);  // select everything from array (but it's unimportant here)

        $bar07 = new DummyArraySelection($f07, $s07, ['baz' => 'BAZ']);
        $qux07 = new DummyArraySelection($f07, $s07, ['cez' => 'CEZ']);

        $a07 = new DummyArraySelection(new DummyArrayResultFactory(), $s07, [
            'foo' => [
                'qux' => $qux07,
            ],
            'bar' => $bar07,
        ]);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'array'  => $a07,
            'result' => new DummyArrayResult(false, [
                'foo' => [
                    'qux' => $qux07,
                ],
                'bar' => $bar07,
            ]),
        ];

        //
        // 08
        //

        $f08 = new DummyResultFactory(true); // supports everything but returns non-array result (including iterable)
        $s08 = new DummyValueSelector(true);  // select everything from array (but it's unimportant here)

        $bar08 = new DummyArraySelection($f08, $s08, ['baz' => 'BAZ']);
        $qux08 = new DummyArraySelection($f08, $s08, ['cez' => 'CEZ']);

        $a08 = new DummyArraySelection(new DummyArrayResultFactory(), $s08, [
            'foo' => [
                'qux' => $qux08,
            ],
            'bar' => $bar08,
        ]);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'array'  => $a08,
            'result' => new DummyArrayResult(false, [
                'foo' => [
                    'qux' => $qux08,
                ],
                'bar' => $bar08,
            ]),
        ];
    }

    /**
     * @dataProvider provWithRecursiveTraversal
     *
     * @param mixed $result
     *
     * @psalm-param CtorArgs  $ctor
     * @psalm-param ArrayLike $array
     */
    public function testWithRecursiveTraversal(array $ctor, iterable $array, $result): void
    {
        $visitor = new RecursiveExpectedResultFactoryVisitor(...$ctor);
        $traversal = new RecursiveTraversal();

        $visitor->begin();
        $traversal->walk($array, $visitor);
        $visitor->end();

        $expect = $result;
        $actual = $visitor->result();

        $resultUnwrapper = RecursiveResultUnwrapper::create();

        if ($expect instanceof ResultInterface) {
            $expect = $resultUnwrapper->unwrap(false, $expect);
        }

        if ($actual instanceof ResultInterface) {
            $actual = $resultUnwrapper->unwrap(false, $actual);
        }

        $this->assertSame($expect, $actual);
    }

    public function testMakeStackItemThrowsInternalError(): void
    {
        $visitor = new RecursiveExpectedResultFactoryVisitor();

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('$this->current is null');

        $visitor->makeStackItem([], '', []);
    }

    public function testLeaveThrowsInternalError(): void
    {
        $visitor = new RecursiveExpectedResultFactoryVisitor();

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('$this->current is null');

        $visitor->leave([], [], true);
    }
}
// vim: syntax=php sw=4 ts=4 et:
