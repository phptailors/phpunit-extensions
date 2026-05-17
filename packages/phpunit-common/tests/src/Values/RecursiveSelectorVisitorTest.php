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
use Tailors\PHPUnit\InternalErrorException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type StackItem     = RecursiveSelectorStackItem
 * @psalm-type CtorArgs      = list{ValueSelectorInterface, mixed}
 * @psalm-type EnterTestCall = array{args: array{node: array|ValuesInterface}, return: bool, next?: mixed}
 * @psalm-type VisitTestCall = array{args: array{node: mixed}, key?:array-key}
 */
#[CoversClass(RecursiveSelectorVisitor::class)]
#[Small]
final class RecursiveSelectorVisitorTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function testImplementsRecursiveVisitorInterface(): void
    {
        $valueSelector = new DummyValueSelector();
        self::assertInstanceOf(RecursiveVisitorInterface::class, new RecursiveSelectorVisitor($valueSelector, null));
    }

    public function testInitialResult(): void
    {
        $valueSelector = new DummyValueSelector();
        $visitor = new RecursiveSelectorVisitor($valueSelector, null);
        $this->assertNull($visitor->result());
    }

    /**
     * @return iterable<string, array{path: list<array-key>, expect: string}>
     */
    public static function provCycle(): iterable
    {
        //
        // 01
        //

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'stack'  => [],
            'expect' => '',
        ];

        //
        // 02
        //

        $s02 = array_map(fn ($key) => new RecursiveSelectorStackItem([], $key, new RecursiveSelectorState([], [])), ['foo', 3, 'bar']);

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'stack'  => $s02,
            'expect' => "['foo'][3]['bar']",
        ];

        //
        // 03
        //

        $s03 = array_map(fn ($key) => new RecursiveSelectorStackItem([], $key, new RecursiveSelectorState([], [])), [null, 3, false]);

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'stack'  => $s03,
            'expect' => '[NULL][3][false]',
        ];
    }

    /**
     * @psalm-param list<StackItem> $stack
     */
    #[DataProvider('provCycle')]
    public function testCycle(array $stack, string $expect): void
    {
        $rePath = preg_quote($expect, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested values at \\\$values{$rePath}\\.$/");

        $valueSelector = new DummyValueSelector();
        new RecursiveSelectorVisitor($valueSelector, null)->cycle([], $stack);
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
        $selector = self::getArrayObjectSelector();

        //
        // 01
        //

        $c01 = [new DummyValueSelector(), 'FOO'];
        $e01 = new ExpectedValues();

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'  => [new DummyValueSelector(false), 'FOO'],
            'calls' => [
                [
                    'args' => [
                        'node' => new ExpectedValues(),
                    ],
                    'return' => false,
                ],
            ],
            'result' => null,
        ];

        //
        // 02
        //

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'  => [new DummyValueSelector(false), 'FOO'],
            'calls' => [
                [
                    'args' => [
                        'node' => new ActualValues(),
                    ],
                    'return' => false,
                ],
            ],
            'result' => null,
        ];

        //
        // 03
        //

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'  => [new DummyValueSelector(true), 'FOO'],
            'calls' => [
                [
                    'args' => [
                        'node' => [],
                    ],
                    'return' => false,
                ],
            ],
            'result' => null,
        ];

        //
        // 04
        //

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'  => [new DummyValueSelector(true), 'FOO'],
            'calls' => [
                [
                    'args' => [
                        'node' => new ExpectedValues(),
                    ],
                    'return' => true,
                ],
            ],
            'result' => new ActualValues(),
        ];

        //
        // 05
        //

        $c05 = [
            $selector,
            new \ArrayObject([
                'foo' => new \ArrayObject([
                    'bar' => [],
                ]),
            ]),
        ];
        $e05 = new ExpectedValues([
            'foo' => new ExpectedValues([
                'bar' => ['unimportant'],
            ]),
        ]);

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'  => $c05,
            'calls' => [
                [
                    'args' => [
                        'node' => $e05,
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args' => [
                        'node' => $e05['foo'],
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args' => [
                        'node' => $e05['foo']['bar'],
                    ],
                    'return' => true,
                    'next'   => 0,
                ],
            ],
            'result' => new ActualValues([
                'foo' => new ActualValues([
                    'bar' => [],
                ]),
            ]),
        ];

        //
        // 06
        //

        $c06 = [
            $selector,
            new \ArrayObject([
                'foo' => new \ArrayObject([
                    'bar' => 'nonIterable',
                ]),
            ]),
        ];
        $e06 = new ExpectedValues([
            'foo' => new ExpectedValues([
                'bar' => ['unimportant'],
            ]),
        ]);

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'  => $c06,
            'calls' => [
                [
                    'args' => [
                        'node' => $e06,
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args' => [
                        'node' => $e06['foo'],
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args' => [
                        'node' => $e06['foo']['bar'],
                    ],
                    'return' => false,
                ],
            ],
            'result' => new ActualValues([
                'foo' => new ActualValues([]),
            ]),
        ];

        //
        // 07
        //

        $c07 = [
            $selector,
            new \ArrayObject([
                'foo' => [
                    'bar' => new \ArrayObject([
                        'noniterable',
                    ]),
                ],
            ]),
        ];
        $e07 = new ExpectedValues([
            'foo' => [
                'bar' => new ExpectedValues([
                    'unimportant',
                ]),
            ],
        ]);

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'  => $c07,
            'calls' => [
                [
                    'args' => [
                        'node' => $e07,
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args' => [
                        'node' => $e07['foo'],
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args' => [
                        'node' => $e07['foo']['bar'],
                    ],
                    'return' => true,
                    'next'   => 0,
                ],
            ],
            'result' => new ActualValues([
                'foo' => [
                    'bar' => new ActualValues([]),
                ],
            ]),
        ];

        //
        // 08
        //

        $c08 = [$selector, new \ArrayObject([
            'foo' => [
                'bar' => [],
            ],
        ])];
        $e08 = new ExpectedValues([
            'foo' => [
                'bar' => [
                    'baz' => [],
                ],
            ],
        ]);

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'  => $c08,
            'calls' => [
                [
                    'args' => [
                        'node' => $e08,
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args' => [
                        'node' => $e08['foo'],
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args' => [
                        'node' => $e08['foo']['bar'],
                    ],
                    'return' => true,
                    'next'   => 'baz',
                ],
                [
                    'args' => [
                        'node' => $e08['foo']['bar']['baz'],
                    ],
                    'return' => false,
                ],
            ],
            'result' => new ActualValues([
                'foo' => [
                    'bar' => [],
                ],
            ]),
        ];
    }

    /**
     * @psalm-param CtorArgs                      $ctor
     * @psalm-param non-empty-list<EnterTestCall> $calls
     */
    #[DataProvider('provEnterLeave')]
    public function testEnterLeave(array $ctor, array $calls, mixed $result): void
    {
        $visitor = new RecursiveSelectorVisitor(...$ctor);
        $stack = [];

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

        $expect = $result;
        if ($expect instanceof ActualValues) {
            $expect = new RecursiveUnwrapper()->unwrap($expect);
        }

        $actual = $visitor->result();
        if ($actual instanceof ActualValues) {
            $actual = new RecursiveUnwrapper()->unwrap($actual);
        }

        $this->assertSame($expect, $actual);
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctor: array{0: ValueSelectorInterface, 1: mixed},
     *      calls: non-empty-list<VisitTestCall>,
     *      result: mixed
     *  }>
     */
    public static function provVisit(): iterable
    {
        $selector = self::getArrayObjectSelector();

        //
        // 01
        //

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'  => [new DummyValueSelector(), 'FOO'],
            'enter' => null,
            'calls' => [
                [
                    'args' => [
                        'node' => null,
                        'iter' => false,
                    ],
                ],
            ],
            'result' => 'FOO',
        ];

        //
        // 02
        //

        $a02 = new \ArrayObject([
            'bar' => 'BAR',
            'xx1' => 'ignored',
            'foo' => 'FOO',
            'xx2' => 'ignored',
        ]);

        $e02 = new ExpectedValues([
            'foo' => 'unimportant',
            'bar' => 'unimportant',
            'gez' => 'unimportant',
        ]);

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'  => [$selector, $a02],
            'enter' => [
                'args' => [
                    'node' => $e02,
                ],
                'return' => true,
            ],
            'calls' => [
                [
                    'args' => [
                        'node' => $e02['foo'],
                    ],
                    'key' => 'foo',
                ],
                [
                    'args' => [
                        'node' => $e02['bar'],
                    ],
                    'key' => 'bar',
                ],
                [
                    'args' => [
                        'node' => $e02['gez'],
                    ],
                    'key' => 'gez',
                ],
            ],
            'result' => new ActualValues([
                'foo' => 'FOO',
                'bar' => 'BAR',
            ]),
        ];

        //
        // 03
        //

        $a03 = ['foo' => 'FOO', 'bar' => 'BAR'];
        $e03 = ['unimportant'];

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'  => [$selector, $a03],
            'enter' => [
                'args' => [
                    'node' => $e03,
                ],
                'return' => true,
            ],
            'calls' => [
                [
                    'args' => [
                        'node' => null,
                    ],
                    'key' => 'foo',
                ],
                [
                    'args' => [
                        'node' => null,
                    ],
                    'key' => 'bar',
                ],
                [
                    'args' => [
                        'node' => null,
                    ],
                    'key' => 'gez',
                ],
            ],
            'result' => [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ],
        ];
    }

    /**
     * @psalm-param array{0: ValueSelectorInterface, 1: mixed} $ctor
     * @psalm-param ?EnterTestCall                             $enter
     * @psalm-param non-empty-list<VisitTestCall>              $calls
     */
    #[DataProvider('provVisit')]
    public function testVisit(array $ctor, ?array $enter, array $calls, mixed $result): void
    {
        $visitor = new RecursiveSelectorVisitor(...$ctor);
        $stack = [];

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

        $expect = $result;
        if ($expect instanceof ActualValues) {
            $expect = new RecursiveUnwrapper()->unwrap($expect);
        }

        $actual = $visitor->result();
        if ($actual instanceof ActualValues) {
            $actual = new RecursiveUnwrapper()->unwrap($actual);
        }

        $this->assertSame($expect, $actual);
    }

    /**
     * @psalm-return iterable<string, array{ctor: CtorArgs, values: ValuesInterface, result: mixed}>
     */
    public static function provWithRecursiveTraversal(): iterable
    {
        $selector = self::getArrayObjectSelector();

        //
        // 01
        //

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'   => [new DummyValueSelector(), 'FOO'],
            'values' => new ExpectedValues([]),
            'result' => 'FOO',
        ];

        //
        // 02
        //

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'   => [$selector, new \ArrayObject([])],
            'values' => new ExpectedValues([]),
            'result' => new ActualValues([]),
        ];

        //
        // 03
        //

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor' => [
                $selector,
                new \ArrayObject([
                    'bar' => 'BAR',
                    'xxx' => 'XXX',
                    'foo' => 'FOO',
                    'yyy' => 'YYY',
                ]),
            ],
            'values' => new ExpectedValues([
                'foo' => 'unimportant',
                'bar' => 'unimportant',
            ]),
            'result' => new ActualValues([
                'foo' => 'FOO',
                'bar' => 'BAR',
            ]),
        ];

        //
        // 04
        //

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor' => [
                $selector,
                new \ArrayObject([
                    'bar' => 'BAR',
                    'xxx' => 'XXX',
                    'foo' => 'FOO',
                    'yyy' => 'YYY',
                ]),
            ],
            'values' => new ExpectedValues([
                'foo' => ['unimportant'],
                'bar' => new ExpectedValues([]),
            ]),
            'result' => new ActualValues([
                'foo' => 'FOO',
                'bar' => 'BAR',
            ]),
        ];

        //
        // 05
        //

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor' => [
                $selector,
                new \ArrayObject([
                    'bar' => 'BAR',
                    'xxx' => 'XXX',
                    'foo' => [
                        'baz' => 'FOO.BAZ',
                        'qux' => 'FOO.QUX',
                    ],
                    'yyy' => 'YYY',
                ]),
            ],
            'values' => new ExpectedValues([
                'foo' => [
                    'qux' => new ExpectedValues([
                        'cez' => 'unimportant',
                    ]),
                ],
                'bar' => new ExpectedValues(['unimportant']),
            ]),
            'result' => new ActualValues([
                'foo' => [
                    'baz' => 'FOO.BAZ',
                    'qux' => 'FOO.QUX',
                ],
                'bar' => 'BAR',
            ]),
        ];

        //
        // 06
        //

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor' => [
                $selector,
                new \ArrayObject([
                    'bar' => 'BAR',
                    'xxx' => 'XXX',
                    'foo' => [
                        'baz' => 'FOO.BAZ',
                        'qux' => new \ArrayObject([
                            'cez' => 'FOO.QUX.CEZ',
                            'boo' => 'FOO.QUX.BOO',
                        ]),
                        'gez' => new \ArrayObject([
                            'kik' => 'FOO.GEZ.KIK',
                            'dud' => 'FOO.GEZ.BUB',
                        ]),
                        'bam' => 'FOO.BAM',
                    ],
                    'yyy' => 'YYY',
                ]),
            ],
            'values' => new ExpectedValues([
                'foo' => [
                    'gez' => new ExpectedValues([
                        'kik' => 'unimportant',
                    ]),
                    'qux' => new ExpectedValues([
                        'cez' => 'unimportant',
                    ]),
                ],
                'bar' => new ExpectedValues(['unimportant']),
            ]),
            'result' => new ActualValues([
                'foo' => [
                    'baz' => 'FOO.BAZ',
                    'qux' => new ActualValues([
                        'cez' => 'FOO.QUX.CEZ',
                    ]),
                    'gez' => new ActualValues([
                        'kik' => 'FOO.GEZ.KIK',
                    ]),
                    'bam' => 'FOO.BAM',
                ],
                'bar' => 'BAR',
            ]),
        ];

        //
        // 07
        //
        $s07 = self::getExceptionPropertySelector();

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor'   => [$selector, new \Exception('foo', 123)],
            'values' => new DummyExpectedValues($s07, [
                'message'  => 'unimportant',
                'nonexist' => 'unimportant',
            ]),
            'result' => new DummyValues(true, [
                'message' => 'foo',
            ]),
        ];

        yield 'RecursiveSelectorVisitorTest.php:'.__LINE__ => [
            'ctor' => [
                $selector,
                new \ArrayObject([
                    'f' => 'F',
                    'e' => new \Exception('foo', 123),
                    'd' => 'D',
                ]),
            ],
            'values' => new ExpectedValues([
                'e' => new DummyExpectedValues($s07, [
                    'message'  => 'unimportant',
                    'nonexist' => 'unimportant',
                ]),
                'f' => 'unimportant',
            ]),
            'result' => new ActualValues([
                'e' => new DummyValues(true, [
                    'message' => 'foo',
                ]),
                'f' => 'F',
            ]),
        ];
    }

    /**
     * @psalm-param CtorArgs $ctor
     */
    #[DataProvider('provWithRecursiveTraversal')]
    public function testWithRecursiveTraversal(array $ctor, ValuesInterface $values, mixed $result): void
    {
        $visitor = new RecursiveSelectorVisitor(...$ctor);
        $traversal = new RecursiveTraversal();
        $traversal->walk($values, $visitor);

        $expect = $result;
        $actual = $visitor->result();

        if ($expect instanceof ValuesInterface && $expect->actual()) {
            $expect = new RecursiveUnwrapper()->unwrap($expect);
        }

        if ($actual instanceof ValuesInterface && $actual->actual()) {
            $actual = new RecursiveUnwrapper()->unwrap($actual);
        }

        $this->assertSame($expect, $actual);
    }

    public function testEnterThrowsInternalError(): void
    {
        $visitor = new RecursiveSelectorVisitor(new DummyValueSelector(), null);
        $stack = [new RecursiveSelectorStackItem([], '', new RecursiveSelectorState(null, []))];

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('$stack[0]->node() is an array, but $stack[0]->state()->subject is not');

        $visitor->enter([], $stack);
    }

    public function testMakeStackItemThrowsInternalError(): void
    {
        $visitor = new RecursiveSelectorVisitor(new DummyValueSelector(), null);

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('$this->state is null');

        $visitor->makeStackItem([], '', []);
    }

    public function testLeaveThrowsInternalError(): void
    {
        $visitor = new RecursiveSelectorVisitor(new DummyValueSelector(), null);

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('$this->state is null while $iterating');

        $visitor->leave([], [], true);
    }

    private static function getArrayObjectSelector(): DummyValueSelector
    {
        return new DummyValueSelector(
            fn ($subject): bool => is_object($subject) && \ArrayObject::class === $subject::class,
            function ($subject, $key, &$retval): bool {
                if (!$subject->offsetExists($key)) {
                    return false;
                }
                $retval = $subject[$key];

                return true;
            }
        );
    }

    private static function getExceptionPropertySelector(): DummyValueSelector
    {
        return new DummyValueSelector(
            fn ($subject): bool => is_object($subject) && $subject instanceof \Exception,
            function ($subject, $key, &$retval): bool {
                if (!is_object($subject) || !$subject instanceof \Exception) {
                    return false;
                }

                switch ($key) {
                    case 'message':
                        $retval = $subject->getMessage();

                        return true;

                    case 'code':
                        $retval = $subject->getCode();

                        return true;
                }

                return false;
            }
        );
    }
}
// vim: syntax=php sw=4 ts=4 et:
