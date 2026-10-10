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
use Tailors\PHPUnit\ArraySpec\DummyResultFactoryAndValueSelectorWrapper;
use Tailors\PHPUnit\ArraySpec\DummyResultFactoryWrapper;
use Tailors\PHPUnit\ArraySpec\DummyValueSelectorWrapper;
use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\InternalErrorException;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapper;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversal;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorInterface;
use Tailors\PHPUnit\Result\DummyTaggedArrayResult;
use Tailors\PHPUnit\Result\ResultInterface;
use Tailors\PHPUnit\ResultFactory\DummyResultFactory;
use Tailors\PHPUnit\ResultFactory\DummyTaggedArrayResultFactory;
use Tailors\PHPUnit\ValueSelector\DummyValueSelector;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveResultFactory\RecursiveActualResultFactoryVisitor
 * @covers \Tailors\PHPUnit\RecursiveResultFactory\RecursiveActualResultFactoryVisitorTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type StackItem     = RecursiveActualResultFactoryStackItem
 * @psalm-type BeginArgs     = list{mixed}
 * @psalm-type EnterTestCall = array{args: array{node: ArrayLike}, return: bool, next?: array-key}
 * @psalm-type VisitTestCall = array{args: array{node: mixed, iter?: bool}, key?: array-key}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class RecursiveActualResultFactoryVisitorTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsRecursiveActualResultFactoryVisitorInterface(): void
    {
        self::assertInstanceOf(RecursiveActualResultFactoryVisitorInterface::class, new RecursiveActualResultFactoryVisitor());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsRecursiveVisitorInterface(): void
    {
        self::assertInstanceOf(RecursiveVisitorInterface::class, new RecursiveActualResultFactoryVisitor());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInitialResult(): void
    {
        $visitor = new RecursiveActualResultFactoryVisitor();
        $this->assertNull($visitor->result());
    }

    /**
     * @psalm-return iterable<string, array{stack: list<StackItem>, expect: string}>
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
            return new RecursiveActualResultFactoryStackItem([], $key, new SubjectResultCouple([], []));
        }, ['foo', 3, 'bar']);

        yield basename(__FILE__).':'.__LINE__ => [
            'stack'  => $s02,
            'expect' => "['foo'][3]['bar']",
        ];

        //
        // 03
        //

        $s03 = array_map(function ($key) {
            /** @psalm-suppress PossiblyNullArgument,PossiblyFalseArgument */
            return new RecursiveActualResultFactoryStackItem([], $key, new SubjectResultCouple([], []));
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
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCycle(array $stack, string $expect): void
    {
        $rePath = preg_quote($expect, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested array at \\\$array{$rePath}\\.$/");

        (new RecursiveActualResultFactoryVisitor())->cycle([], $stack);
    }

    /**
     * @psalm-return iterable<string, array{
     *      begin: BeginArgs,
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
            'begin'  => ['FOO'],
            'calls'  => [
                [
                    'args'   => [
                        'node' => [],
                    ],
                    'return' => false,
                ],
            ],
            'result' => null,
        ];

        //
        // 02
        //
        $e02 = new DummyResultFactoryAndValueSelectorWrapper(
            new DummyTaggedArrayResultFactory(),
            new DummyValueSelector(false),
            ['unimportant' => 'UNIMPORTANT']
        );

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => ['FOO'],
            'calls'  => [
                [
                    'args'   => [
                        'node' => $e02,
                    ],
                    'return' => false,
                ],
            ],
            'result' => null,
        ];

        //
        // 03
        //

        $e03 = new DummyResultFactoryAndValueSelectorWrapper(
            new DummyTaggedArrayResultFactory(),
            new DummyValueSelector(true),
            ['unimportant' => 'UNIMPORTANT']
        );

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => ['FOO'],
            'calls'  => [
                [
                    'args'   => [
                        'node' => $e03,
                    ],
                    'return' => true,
                ],
            ],
            'result' => new DummyTaggedArrayResult(true, []),
        ];

        //
        // 04
        //

        $e04 = new DummyResultFactoryWrapper(
            new DummyTaggedArrayResultFactory(),
            ['foo' => 'FOO']
        );

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => ['FOO'],
            'calls'  => [
                [
                    'args'   => [
                        'node' => $e04,
                    ],
                    'return' => false, // not supports 'FOO'
                ],
            ],
            'result' => null,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [['bar' => 'BAR']],
            'calls'  => [
                [
                    'args'   => [
                        'node' => $e04,
                    ],
                    'return' => true,
                ],
            ],
            'result' => new DummyTaggedArrayResult(true, ['bar' => 'BAR']),
        ];

        //
        // 05
        //

        $a05 = new \ArrayObject([
            'foo' => new \ArrayObject([
                'bar' => [],
            ]),
        ]);

        $f05 = new DummyTaggedArrayResultFactory();
        $s05 = self::getArrayObjectSelector();

        $e05 = new DummyResultFactoryAndValueSelectorWrapper($f05, $s05, [
            'foo' => new DummyResultFactoryAndValueSelectorWrapper($f05, $s05, [
                'bar' => ['unimportant'],
            ]),
        ]);

        /** @psalm-var DummyResultFactoryAndValueSelectorWrapper $e05foo */
        $e05foo = $e05['foo'];

        /** @psalm-var array $e05foobar */
        $e05foobar = $e05foo['bar'];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$a05],
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
                        'node' => $e05foo,
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args'   => [
                        'node' => $e05foobar,
                    ],
                    'return' => true,
                    'next'   => 0,
                ],
            ],
            'result' => new DummyTaggedArrayResult(true, [
                'foo' => new DummyTaggedArrayResult(true, [
                    'bar' => [],
                ]),
            ]),
        ];

        //
        // 06
        //

        $a06 = new \ArrayObject([
            'foo' => new \ArrayObject([
                'bar' => 'unselectable',
            ]),
        ]);

        $f06 = new DummyTaggedArrayResultFactory();
        $s06 = self::getArrayObjectSelector();

        $e06 = new DummyResultFactoryAndValueSelectorWrapper($f06, $s06, [
            'foo' => new DummyResultFactoryAndValueSelectorWrapper($f06, $s06, [
                'bar' => ['unimportant'],
            ]),
        ]);

        /** @psalm-var DummyResultFactoryAndValueSelectorWrapper $e06foo */
        $e06foo = $e06['foo'];

        /** @psalm-var array $e06foobar */
        $e06foobar = $e06foo['bar'];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$a06],
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
                        'node' => $e06foo,
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args'   => [
                        'node' => $e06foobar,
                    ],
                    'return' => false,
                ],
            ],
            'result' => new DummyTaggedArrayResult(true, [
                'foo' => new DummyTaggedArrayResult(true, []),
            ]),
        ];

        //
        // 07
        //

        $a07 = new \ArrayObject([
            'foo' => [
                'bar' => new \ArrayObject([
                    'unselectable',
                ]),
            ],
        ]);

        $f07 = new DummyTaggedArrayResultFactory();
        $s07 = self::getArrayObjectSelector();

        $e07 = new DummyResultFactoryAndValueSelectorWrapper($f07, $s07, [
            'foo' => [
                'bar' => new DummyResultFactoryAndValueSelectorWrapper($f07, $s07, [
                    'unimportant',
                ]),
            ],
        ]);

        /** @psalm-var array $e07foo */
        $e07foo = $e07['foo'];

        /** @psalm-var DummyResultFactoryAndValueSelectorWrapper $e07foobar */
        $e07foobar = $e07foo['bar'];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$a07],
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
                        'node' => $e07foo,
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args'   => [
                        'node' => $e07foobar,
                    ],
                    'return' => true,
                    'next'   => 0,
                ],
            ],
            'result' => new DummyTaggedArrayResult(true, [
                'foo' => [
                    'bar' => new DummyTaggedArrayResult(true, []),
                ],
            ]),
        ];

        //
        // 08
        //

        $a08 = new \ArrayObject([
            'foo' => [
                'bar' => [],
            ],
        ]);

        $f08 = new DummyTaggedArrayResultFactory();
        $s08 = self::getArrayObjectSelector();

        $e08 = new DummyResultFactoryAndValueSelectorWrapper($f08, $s08, [
            'foo' => [
                'bar' => [
                    'baz' => [],
                ],
            ],
        ]);

        /** @psalm-var array $e08foo */
        $e08foo = $e08['foo'];

        /** @psalm-var array $e08foobar */
        $e08foobar = $e08foo['bar'];

        /** @psalm-var array $e08foobarbaz */
        $e08foobarbaz = $e08foobar['baz'];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$a08],
            'calls'  => [
                [
                    'args'   => [
                        'node' => $e08,
                    ],
                    'return' => true,
                    'next'   => 'foo',
                ],
                [
                    'args'   => [
                        'node' => $e08foo,
                    ],
                    'return' => true,
                    'next'   => 'bar',
                ],
                [
                    'args'   => [
                        'node' => $e08foobar,
                    ],
                    'return' => true,
                    'next'   => 'baz',
                ],
                [
                    'args'   => [
                        'node' => $e08foobarbaz,
                    ],
                    'return' => false,
                ],
            ],
            'result' => new DummyTaggedArrayResult(true, [
                'foo' => [
                    'bar' => [],
                ],
            ]),
        ];
    }

    /**
     * @dataProvider provEnterLeave
     *
     * @param mixed $result
     *
     * @psalm-param BeginArgs                     $begin
     * @psalm-param non-empty-list<EnterTestCall> $calls
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testEnterLeave(array $begin, array $calls, $result): void
    {
        $visitor = new RecursiveActualResultFactoryVisitor();
        $stack = [];

        $visitor->begin(...$begin);

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
                $this->assertNotEmpty($stack);
                $visitor->freeStackItem(array_pop($stack), $stack);
            }
            $this->assertNull($visitor->leave($args['node'], $stack, $call['return']));
        }

        $visitor->end();

        /** @psalm-var mixed $expect */
        $expect = self::unwrapWithResultUnwrapper($result);

        /** @psalm-var mixed $actual */
        $actual = self::unwrapWithResultUnwrapper($visitor->result());

        $this->assertSame($expect, $actual);
    }

    /**
     * @psalm-return iterable<string, array{
     *      begin: BeginArgs,
     *      enter: ?EnterTestCall,
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
            'begin'  => ['FOO'],
            'enter'  => null,
            'calls'  => [
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

        $f02 = new DummyTaggedArrayResultFactory();
        $s02 = self::getArrayObjectSelector();

        $e02 = new DummyResultFactoryAndValueSelectorWrapper($f02, $s02, [
            'foo' => 'unimportant',
            'bar' => 'unimportant',
            'gez' => 'unimportant',
        ]);

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$a02],
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
            'result' => new DummyTaggedArrayResult(true, [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ]),
        ];

        //
        // 03
        //

        $a03 = ['foo' => 'FOO', 'bar' => 'BAR'];
        $e03 = ['unimportant'];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$a03],
            'enter'  => [
                'args'   => [
                    'node' => $e03,
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
                'foo' => 'FOO',
                'bar' => 'BAR',
            ],
        ];

        //
        // 04
        //

        $a04 = [
            'foo' => 'FOO',
            'bar' => 'BAR',
        ];
        $c04 = 0;
        $f04 = new DummyTaggedArrayResultFactory();
        $s04 = new DummyValueSelector(
            function () use (&$c04): bool {
                // A selector which changes its mind everytime.
                return (bool) ((++$c04) % 2);
            },
            /**
             * @param mixed $subject
             * @param mixed $key
             * @param mixed $retval
             *
             * @psalm-param array-key $key
             *
             * @psalm-param-out mixed $retval
             */
            function ($subject, $key, &$retval) use ($c04): bool {
                if (!($c04 % 2) || !is_array($subject) || !array_key_exists($key, $subject)) {
                    return false;
                }

                /** @psalm-var mixed $retval */
                $retval = $subject[$key];

                return true;
            }
        );
        $e04 = new DummyResultFactoryAndValueSelectorWrapper($f04, $s04, [
            'foo' => 'UNIMPORTANT',
            'bar' => 'UNIMPORTANT',
        ]);

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$a04],
            'enter'  => [
                'args'   => [
                    'node' => $e04,
                ],
                'return' => true,
            ],
            'calls'  => [
                [
                    'args' => [
                        'node' => $a04['foo'],
                    ],
                    'key'  => 'foo',
                ],
            ],
            'result' => new DummyTaggedArrayResult(true, []),
        ];
    }

    /**
     * @dataProvider provVisit
     *
     * @param mixed $result
     *
     * @psalm-param BeginArgs                     $begin
     * @psalm-param ?EnterTestCall                $enter
     * @psalm-param non-empty-list<VisitTestCall> $calls
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testVisit(array $begin, ?array $enter, array $calls, $result): void
    {
        $visitor = new RecursiveActualResultFactoryVisitor();
        $stack = [];

        $visitor->begin(...$begin);

        if (null !== $enter) {
            $args = $enter['args'];
            $this->assertSame($enter['return'], $visitor->enter($args['node'], $stack));
        }

        $iter = null === $enter ? false : $enter['return'];

        foreach ($calls as $call) {
            $args = $call['args'];
            if (array_key_exists('key', $call)) {
                $this->assertNotNull($enter);
                array_push($stack, $visitor->makeStackItem($enter['args']['node'], $call['key'], $stack));
            }
            $this->assertNull($visitor->visit($args['node'], $stack, $iter));
            if (array_key_exists('key', $call)) {
                $this->assertNotEmpty($stack);
                $visitor->freeStackItem(array_pop($stack), $stack);
            }
        }

        if (null !== $enter) {
            $args = $enter['args'];
            $this->assertNull($visitor->leave($args['node'], $stack, $enter['return']));
        }

        $visitor->end();

        /** @psalm-var mixed $expect*/
        $expect = self::unwrapWithResultUnwrapper($result);

        /** @psalm-var mixed $actual*/
        $actual = self::unwrapWithResultUnwrapper($visitor->result());

        $this->assertSame($expect, $actual);
    }

    /**
     * @psalm-return \Generator<non-falsy-string, array{
     *      begin: BeginArgs,
     *      array: ArrayLike,
     *      result: mixed
     *  }>
     */
    public static function provWithRecursiveTraversal(): iterable
    {
        //
        // 01
        //

        $f01 = new DummyTaggedArrayResultFactory();
        $s01 = new DummyValueSelector(false);

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => ['FOO'],
            'array'  => new DummyResultFactoryAndValueSelectorWrapper($f01, $s01, []),
            'result' => 'FOO',
        ];

        //
        // 02
        //

        $f02 = new DummyTaggedArrayResultFactory();
        $s02 = self::getArrayObjectSelector();

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [new \ArrayObject([])],
            'array'  => new DummyResultFactoryAndValueSelectorWrapper($f02, $s02, []),
            'result' => new DummyTaggedArrayResult(true, []),
        ];

        //
        // 03
        //

        $f03 = new DummyTaggedArrayResultFactory();
        $s03 = self::getArrayObjectSelector();

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [
                new \ArrayObject([
                    'bar' => 'BAR',
                    'xxx' => 'XXX',
                    'foo' => 'FOO',
                    'yyy' => 'YYY',
                ]),
            ],
            'array'  => new DummyResultFactoryAndValueSelectorWrapper($f03, $s03, [
                'foo' => 'unimportant',
                'bar' => 'unimportant',
            ]),
            'result' => new DummyTaggedArrayResult(true, [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ]),
        ];

        //
        // 04
        //

        $f04 = new DummyTaggedArrayResultFactory();
        $s04 = self::getArrayObjectSelector();

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [
                new \ArrayObject([
                    'bar' => 'BAR',
                    'xxx' => 'XXX',
                    'foo' => 'FOO',
                    'yyy' => 'YYY',
                ]),
            ],
            'array'  => new DummyResultFactoryAndValueSelectorWrapper($f04, $s04, [
                'foo' => ['unimportant'],
                'bar' => new DummyTaggedArrayResult(false, []),
            ]),
            'result' => new DummyTaggedArrayResult(true, [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ]),
        ];

        //
        // 05
        //

        $f05 = new DummyTaggedArrayResultFactory();
        $s05 = self::getArrayObjectSelector();

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [
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
            'array'  => new DummyResultFactoryAndValueSelectorWrapper($f05, $s05, [
                'foo' => [
                    'qux' => new DummyResultFactoryAndValueSelectorWrapper($f05, $s05, [
                        'cez' => 'unimportant',
                    ]),
                ],
                'bar' => new DummyResultFactoryAndValueSelectorWrapper($f05, $s05, ['unimportant']),
            ]),
            'result' => new DummyTaggedArrayResult(true, [
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

        $f06 = new DummyTaggedArrayResultFactory();
        $s06 = self::getArrayObjectSelector();

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [
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
            'array'  => new DummyResultFactoryAndValueSelectorWrapper($f06, $s06, [
                'foo' => [
                    'gez' => new DummyResultFactoryAndValueSelectorWrapper($f06, $s06, [
                        'kik' => 'unimportant',
                    ]),
                    'qux' => new DummyResultFactoryAndValueSelectorWrapper($f06, $s06, [
                        'cez' => 'unimportant',
                    ]),
                ],
                'bar' => new DummyResultFactoryAndValueSelectorWrapper($f06, $s06, ['unimportant']),
            ]),
            'result' => new DummyTaggedArrayResult(true, [
                'foo' => [
                    'baz' => 'FOO.BAZ',
                    'qux' => new DummyTaggedArrayResult(true, [
                        'cez' => 'FOO.QUX.CEZ',
                    ]),
                    'gez' => new DummyTaggedArrayResult(true, [
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

        $f07a = new DummyTaggedArrayResultFactory('TAG-A');
        $f07e = new DummyTaggedArrayResultFactory('TAG-E');
        $s07a = self::getArrayObjectSelector();
        $s07e = self::getExceptionPropertySelector();

        $o06 = new \stdClass();

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$o06],
            'array'  => new DummyResultFactoryAndValueSelectorWrapper($f07e, $s07e, [
                'message'  => 'unimportant',
                'nonexist' => 'unimportant',
            ]),
            'result' => $o06,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [new \Exception('foo', 123)],
            'array'  => new DummyResultFactoryAndValueSelectorWrapper($f07e, $s07e, [
                'message'  => 'unimportant',
                'nonexist' => 'unimportant',
            ]),
            'result' => new DummyTaggedArrayResult(true, [
                'message' => 'foo',
            ], 'TAG-E'),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [
                new \ArrayObject([
                    'f' => 'F',
                    'e' => new \Exception('foo', 123),
                    'd' => 'D',
                ]),
            ],
            'array'  => new DummyResultFactoryAndValueSelectorWrapper($f07a, $s07a, [
                'e' => new DummyResultFactoryAndValueSelectorWrapper($f07e, $s07e, [
                    'message'  => 'unimportant',
                    'nonexist' => 'unimportant',
                ]),
                'f' => 'unimportant',
            ]),
            'result' => new DummyTaggedArrayResult(true, [
                'e' => new DummyTaggedArrayResult(true, [
                    'message' => 'foo',
                ], 'TAG-E'),
                'f' => 'F',
            ], 'TAG-A'),
        ];

        //
        // 08
        //

        $f08a = new DummyTaggedArrayResultFactory('TAG-A');
        $f08e = new DummyTaggedArrayResultFactory('TAG-E');
        $s08e = self::getExceptionPropertySelector();

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [
                new \ArrayObject([
                    'f' => 'F',
                    'e' => new \Exception('foo', 123),
                    'd' => 'D',
                ]),
            ],
            'array'  => new DummyResultFactoryWrapper($f08a, [
                'e' => new DummyResultFactoryAndValueSelectorWrapper($f08e, $s08e, [
                    'message'  => 'unimportant',
                    'nonexist' => 'unimportant',
                ]),
                'f' => 'unimportant',
            ]),
            'result' => new DummyTaggedArrayResult(true, [
                'f' => 'F',
                'e' => new DummyTaggedArrayResult(true, [
                    'message' => 'foo',
                ], 'TAG-E'),
                'd' => 'D',
            ], 'TAG-A'),
        ];

        //
        // 09
        //

        $f09a = new DummyTaggedArrayResultFactory('TAG-A', function ($input) {
            if ($input instanceof \Traversable) {
                $input = iterator_to_array($input);
            }
            ksort($input);

            return $input;
        });

        $f09e = new DummyTaggedArrayResultFactory('TAG-E');
        $s09e = self::getExceptionPropertySelector();

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [
                new \ArrayObject([
                    'f' => 'F',
                    'e' => new \Exception('foo', 123),
                    'd' => 'D',
                ]),
            ],
            'array'  => new DummyResultFactoryWrapper($f09a, [
                'x' => 'UNIMPORTANT',
                'e' => new DummyResultFactoryAndValueSelectorWrapper($f09e, $s09e, [
                    'message'  => 'unimportant',
                    'nonexist' => 'unimportant',
                    'code'     => 'unimportant',
                ]),
                'f' => 'UNIMPORTANT',
            ]),
            'result' => new DummyTaggedArrayResult(true, [
                'd' => 'D',
                'e' => new DummyTaggedArrayResult(true, [
                    'message' => 'foo',
                    'code'    => 123,
                ], 'TAG-E'),
                'f' => 'F',
            ], 'TAG-A'),
        ];

        //
        // 10
        //

        $f10a = new DummyTaggedArrayResultFactory('TAG-A');
        $s10a = self::getNamespaceVariableSelector([
            'ns1' => [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ],
            'ns2' => [
                'baz' => 'BAZ',
                'gez' => new \ArrayObject([
                    'qux' => 'QUX',
                    'cop' => 'COP',
                ]),
            ],
        ]);

        $f10b = new DummyTaggedArrayResultFactory('TAG-B');
        $s10b = self::getArrayObjectSelector();

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [['cez' => 'CEZ']],
            'array'  => new DummyResultFactoryAndValueSelectorWrapper($f10a, $s10a, [
                'foo' => 'UNIMPORTANT',
                'baz' => 'UNIMPORTANT',
            ]),
            'result' => ['cez' => 'CEZ'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => ['ns1'],
            'array'  => new DummyResultFactoryAndValueSelectorWrapper($f10a, $s10a, [
                'foo' => 'UNIMPORTANT',
                'baz' => 'UNIMPORTANT',
            ]),
            'result' => new DummyTaggedArrayResult(true, [
                'foo' => 'FOO',
            ], 'TAG-A'),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => ['ns2'],
            'array'  => new DummyResultFactoryAndValueSelectorWrapper($f10a, $s10a, [
                'foo' => 'UNIMPORTANT',
                'baz' => 'UNIMPORTANT',
            ]),
            'result' => new DummyTaggedArrayResult(true, [
                'baz' => 'BAZ',
            ], 'TAG-A'),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => ['ns2'],
            'array'  => new DummyResultFactoryAndValueSelectorWrapper($f10a, $s10a, [
                'gez' => new DummyResultFactoryAndValueSelectorWrapper($f10b, $s10b, [
                    'cop' => 'UNIMPORTANT',
                    'qux' => ['UNIMPORTANT'],
                    'fix' => ['UNIMPORTANT'],
                ]),
                'baz' => 'UNIMPORTANT',
            ]),
            'result' => new DummyTaggedArrayResult(true, [
                'gez' => new DummyTaggedArrayResult(true, [
                    'cop' => 'COP',
                    'qux' => 'QUX',
                ], 'TAG-B'),
                'baz' => 'BAZ',
            ], 'TAG-A'),
        ];

        //
        // 11
        //

        $a11 = new \ArrayObject([
            'foo' => 'FOO',
            'bar' => 'BAR',
        ]);

        $s11 = self::getArrayObjectSelector();

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$a11],
            'array'  => new DummyValueSelectorWrapper($s11, [
                'foo' => 'UNIMPORTANT',
                'baz' => 'UNIMPORTANT',
            ]),
            'result' => $a11,
        ];

        //
        // 12
        //

        $f12 = new DummyResultFactory(true);

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => ['FOO'],
            'array'  => new DummyResultFactoryWrapper($f12, [
                'foo' => 'UNIMPORTANT',
                'baz' => 'UNIMPORTANT',
            ]),
            'result' => 'FOO',
        ];
    }

    /**
     * @dataProvider provWithRecursiveTraversal
     *
     * @param mixed $result
     *
     * @psalm-param BeginArgs $begin
     * @psalm-param ArrayLike $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testWithRecursiveTraversal(array $begin, iterable $array, $result): void
    {
        $visitor = new RecursiveActualResultFactoryVisitor();
        $traversal = new RecursiveTraversal();

        $visitor->begin(...$begin);
        $traversal->walk($array, $visitor);
        $visitor->end();

        /** @psalm-var mixed $expect */
        $expect = self::unwrapWithResultUnwrapper($result);

        /** @psalm-var mixed $actual */
        $actual = self::unwrapWithResultUnwrapper($visitor->result());

        $this->assertSame($expect, $actual);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMakeStackItemThrowsInternalError(): void
    {
        $visitor = new RecursiveActualResultFactoryVisitor();

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('$this->current is null');

        $visitor->makeStackItem([], '', []);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLeaveThrowsInternalError(): void
    {
        $visitor = new RecursiveActualResultFactoryVisitor();

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('$this->current is null');

        $visitor->leave([], [], true);
    }

    private static function getArrayObjectSelector(): DummyValueSelector
    {
        return new DummyValueSelector(
            function ($subject): bool {
                return is_object($subject) && \ArrayObject::class === get_class($subject);
            },
            /**
             * @param mixed $key
             * @param mixed $retval
             *
             * @psalm-param array-key $key
             *
             * @psalm-param-out mixed $retval
             */
            function (\ArrayAccess $subject, $key, &$retval): bool {
                if (!$subject->offsetExists($key)) {
                    return false;
                }

                /** @psalm-var mixed $retval */
                $retval = $subject[$key];

                return true;
            }
        );
    }

    private static function getExceptionPropertySelector(): DummyValueSelector
    {
        return new DummyValueSelector(function ($subject): bool {
            return is_object($subject) && $subject instanceof \Exception;
        }, function ($subject, $key, &$retval): bool {
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
        });
    }

    /**
     * @psalm-param array<non-empty-string, array<non-empty-string,mixed>> $namespaces
     */
    private static function getNamespaceVariableSelector(array $namespaces): DummyValueSelector
    {
        return new DummyValueSelector(
            function ($subject) use ($namespaces): bool {
                return is_string($subject) && array_key_exists($subject, $namespaces);
            },
            /**
             * @param mixed $subject
             * @param mixed $key
             * @param mixed $retval
             *
             * @psalm-param array-key $key
             *
             * @psalm-param-out mixed $retval
             */
            function ($subject, $key, &$retval) use ($namespaces): bool {
                if (!is_string($subject) || !array_key_exists($subject, $namespaces)) {
                    return false;
                }

                if (!array_key_exists($key, $namespaces[$subject])) {
                    return false;
                }

                /** @psalm-var mixed $retval */
                $retval = $namespaces[$subject][$key];

                return true;
            }
        );
    }

    /**
     * @param mixed $input
     *
     * @return mixed
     */
    private static function unwrapWithResultUnwrapper($input)
    {
        if (is_array($input) || is_iterable($input) && $input instanceof ResultInterface) {
            /** @psalm-var ArrayLike $input */
            $resultUnwrapper = RecursiveResultUnwrapper::create();

            return $resultUnwrapper->unwrap(true, $input);
        }

        return $input;
    }
}
// vim: syntax=php sw=4 ts=4 et:
