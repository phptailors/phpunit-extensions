<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use Tailors\PHPUnit\ArrayResult\DummyExpectedArrayResult;
use Tailors\PHPUnit\ArrayResult\DummyArrayResult;
use Tailors\PHPUnit\ArrayResult\ExpectedArrayResult;
use Tailors\PHPUnit\ArrayResult\ActualArrayResult;
use Tailors\PHPUnit\ArrayResult\ArrayResultInterface;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ArraySpec\DummyArraySelection;
use Tailors\PHPUnit\ArraySpec\DummyArraySelectionOnly;
use Tailors\PHPUnit\ArraySpec\DummyArraySpec;
use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\InternalErrorException;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapper;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapperVisitor;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversal;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorInterface;
use Tailors\PHPUnit\ResultFactory\DummyArrayResultFactory;
use Tailors\PHPUnit\ResultFactory\DummyResultFactory;
use Tailors\PHPUnit\Result\ResultInterface;
use Tailors\PHPUnit\ValueSelector\DummyValueSelector;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;
use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactoryVisitor
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type StackItem     = RecursiveResultFactoryStackItem
 * @psalm-type CtorArgs      = list{bool, mixed}
 * @psalm-type EnterTestCall = array{args: array{node: array|ValuesInterface}, return: bool, next?: mixed}
 * @psalm-type VisitTestCall = array{args: array{node: mixed}, key?:array-key}
 */
final class RecursiveResultFactoryVisitorTest extends TestCase
{
    public function testImplementsRecursiveVisitorInterface(): void
    {
        self::assertInstanceOf(RecursiveVisitorInterface::class, new RecursiveResultFactoryVisitor(false, null));
    }

    public function testInitialResult(): void
    {
        $visitor = new RecursiveResultFactoryVisitor(false, null);
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

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'stack'  => [],
            'expect' => '',
        ];

        //
        // 02
        //

        $s02 = array_map(function ($key) {
            return new RecursiveResultFactoryStackItem([], $key, new SubjectResultCouple([], []));
        }, ['foo', 3, 'bar']);

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'stack'  => $s02,
            'expect' => "['foo'][3]['bar']",
        ];

        //
        // 03
        //

        $s03 = array_map(function ($key) {
            return new RecursiveResultFactoryStackItem([], $key, new SubjectResultCouple([], []));
        }, [null, 3, false]);

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
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

        (new RecursiveResultFactoryVisitor(false, null))->cycle([], $stack);
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

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, 'FOO'],
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
        // 02
        //
        $e02 =  new DummyArraySelection(
            new DummyArrayResultFactory(),
            new DummyValueSelector(false),
            ['unimportant' => 'UNIMPORTANT']
        );

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, 'FOO'],
            'calls' => [
                [
                    'args' => [
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

        $e03 =  new DummyArraySelection(
            new DummyArrayResultFactory(),
            new DummyValueSelector(true),
            ['unimportant' => 'UNIMPORTANT']
        );

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, 'FOO'],
            'calls' => [
                [
                    'args' => [
                        'node' => $e03,
                    ],
                    'return' => true,
                ],
            ],
            'result' => new DummyArrayResult(false, [])
        ];

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true, 'FOO'],
            'calls' => [
                [
                    'args' => [
                        'node' => $e03,
                    ],
                    'return' => true,
                ],
            ],
            'result' => new DummyArrayResult(true, [])
        ];

        //
        // 04
        //

        $e04 = new DummyArraySpec(
            new DummyArrayResultFactory(),
            ['foo' => 'FOO']
        );

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, 'FOO'],
            'calls' => [
                [
                    'args' => [
                        'node' => $e04,
                    ],
                    'return' => false, // not supports 'FOO'
                ],
            ],
            'result' => null,
        ];

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
           'ctor'  => [false, ['bar' => 'BAR']],
            'calls' => [
                [
                    'args' => [
                        'node' => $e04,
                    ],
                    'return' => true,
                ],
            ],
            'result' => new DummyArrayResult(false, ['bar' => 'BAR']),
        ];

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
           'ctor'  => [true, ['bar' => 'BAR']],
            'calls' => [
                [
                    'args' => [
                        'node' => $e04,
                    ],
                    'return' => true,
                ],
            ],
            'result' => new DummyArrayResult(true, ['bar' => 'BAR']),
        ];

        //
        // 05
        //

        $a05 = new \ArrayObject([
            'foo' => new \ArrayObject([
                'bar' => [],
            ]),
        ]);

        $f05 = new DummyArrayResultFactory();
        $s05 = self::getArrayObjectSelector();

        $e05 = new DummyArraySelection($f05, $s05, [
            'foo' => new DummyArraySelection($f05, $s05, [
                'bar' => ['unimportant'],
            ]),
        ]);

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, $a05],
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
            'result' => new DummyArrayResult(false, [
                'foo' => new DummyArrayResult(false, [
                    'bar' => [],
                ]),
            ]),
        ];

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [true, $a05],
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
            'result' => new DummyArrayResult(true, [
                'foo' => new DummyArrayResult(true, [
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

        $f06 = new DummyArrayResultFactory();
        $s06 = self::getArrayObjectSelector();

        $e06 = new DummyArraySelection($f06, $s06, [
            'foo' => new DummyArraySelection($f06, $s06, [
                'bar' => ['unimportant'],
            ]),
        ]);

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, $a06],
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
            'result' => new DummyArrayResult(false, [
                'foo' => new DummyArrayResult(false, []),
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

        $f07 = new DummyArrayResultFactory();
        $s07 = self::getArrayObjectSelector();

        $e07 = new DummyArraySelection($f07, $s07, [
            'foo' => [
                'bar' => new DummyArraySelection($f07, $s07, [
                    'unimportant',
                ]),
            ],
        ]);

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, $a07],
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
            'result' => new DummyArrayResult(false, [
                'foo' => [
                    'bar' => new DummyArrayResult(false, []),
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

        $f08 = new DummyArrayResultFactory();
        $s08 = self::getArrayObjectSelector();

        $e08 = new DummyArraySelection($f08, $s08, [
            'foo' => [
                'bar' => [
                    'baz' => [],
                ],
            ],
        ]);

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, $a08],
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
            'result' => new DummyArrayResult(false, [
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
     * @psalm-param CtorArgs                      $ctor
     * @psalm-param non-empty-list<EnterTestCall> $calls
     */
    public function testEnterLeave(array $ctor, array $calls, $result): void
    {
        $visitor = new RecursiveResultFactoryVisitor(...$ctor);
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
        if ($expect instanceof ResultInterface) {
            $expect = RecursiveResultUnwrapper::create($ctor[0])->unwrap($expect);
        }

        $actual = $visitor->result();
        if ($actual instanceof ResultInterface) {
            $actual = RecursiveResultUnwrapper::create($ctor[0])->unwrap($actual);
        }

        $this->assertSame($expect, $actual);
    }

    /**
     * @psalm-return iterable<string, array{ctor: array{0: ValueSelectorInterface, 1: mixed}, calls: non-empty-list<VisitTestCall>, result: mixed}>
     */
    public static function provVisit(): iterable
    {
        //
        // 01
        //

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, 'FOO'],
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

        $f02 = new DummyArrayResultFactory();
        $s02 = self::getArrayObjectSelector();

        $e02 = new DummyArraySelection($f02, $s02, [
            'foo' => 'unimportant',
            'bar' => 'unimportant',
            'gez' => 'unimportant',
        ]);

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, $a02],
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
            'result' => new DummyArrayResult(false, [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ]),
        ];

        //
        // 03
        //

        $a03 = ['foo' => 'FOO', 'bar' => 'BAR'];
        $e03 = ['unimportant'];

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, $a03],
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

        //
        // 04
        //

        $a04 = [
            'foo' => 'FOO',
            'bar' => 'BAR',
        ];
        $c04 = 0;
        $f04 = new DummyArrayResultFactory();
        $s04 = new DummyValueSelector(function ($subject) use (&$c04): bool{
            // A selector which changes its mind everytime.
            return (bool)((++$c04) % 2);
        }, function ($subject, $key, &$retval) use ($c04): bool {
            if (!($c04 % 2) || !is_array($subject) || !array_key_exists($key, $subject)) {
                return false;
            }
            $retval = $subject[$key];
            return true;
        });
        $e04 = new DummyArraySelection($f04, $s04, [
            'foo' => 'UNIMPORTANT',
            'bar' => 'UNIMPORTANT',
        ]);

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false, $a04],
            'enter' => [
                'args' => [
                    'node' => $e04,
                ],
                'return' => true,
            ],
            'calls' => [
                [
                    'args' => [
                        'node' => $a04['foo'],
                    ],
                    'key' => 'foo',
                ],
            ],
            'result' => new DummyArrayResult(false, [
            ]),
        ];
    }

    /**
     * @dataProvider provVisit
     *
     * @param mixed $result
     *
     * @psalm-param array{0: ValueSelectorInterface, 1: mixed} $ctor
     * @psalm-param ?EnterTestCall                             $enter
     * @psalm-param non-empty-list<VisitTestCall>              $calls
     */
    public function testVisit(array $ctor, ?array $enter, array $calls, $result): void
    {
        $visitor = new RecursiveResultFactoryVisitor(...$ctor);
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
        if ($expect instanceof ResultInterface) {
            $expect = RecursiveResultUnwrapper::create($ctor[0])->unwrap($expect);
        }

        $actual = $visitor->result();
        if ($actual instanceof ResultInterface) {
            $actual = RecursiveResultUnwrapper::create($ctor[0])->unwrap($actual);
        }

        $this->assertSame($expect, $actual);
    }

    /**
     * @psalm-return \Generator<non-falsy-string, array{ctor: CtorArgs, array: array|\Traversable, result: mixed}>
     */
    public static function provWithRecursiveTraversal(): iterable
    {

        //
        // 01
        //

        $f01 = new DummyArrayResultFactory();
        $s01 = new DummyValueSelector(false);

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'   => [false, 'FOO'],
            'array' => new DummyArraySelection($f01, $s01, []),
            'result' => 'FOO',
        ];

        //
        // 02
        //

        $f02 = new DummyArrayResultFactory();
        $s02 = self::getArrayObjectSelector();

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'   => [false, new \ArrayObject([])],
            'array' => new DummyArraySelection($f02, $s02, []),
            'result' => new DummyArrayResult(false, []),
        ];

        //
        // 03
        //

        $f03 = new DummyArrayResultFactory();
        $s03 = self::getArrayObjectSelector();

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor' => [
                false,
                new \ArrayObject([
                    'bar' => 'BAR',
                    'xxx' => 'XXX',
                    'foo' => 'FOO',
                    'yyy' => 'YYY',
                ]),
            ],
            'array' => new DummyArraySelection($f03, $s03, [
                'foo' => 'unimportant',
                'bar' => 'unimportant',
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
        $s04 = self::getArrayObjectSelector();

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor' => [
                false,
                new \ArrayObject([
                    'bar' => 'BAR',
                    'xxx' => 'XXX',
                    'foo' => 'FOO',
                    'yyy' => 'YYY',
                ]),
            ],
            'array' => new DummyArraySelection($f04, $s04, [
                'foo' => ['unimportant'],
                'bar' => new ExpectedArrayResult([]),
            ]),
            'result' => new DummyArrayResult(false, [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ]),
        ];

        //
        // 05
        //

        $f05 = new DummyArrayResultFactory();
        $s05 = self::getArrayObjectSelector();

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor' => [
                false,
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
            'array' => new DummyArraySelection($f05, $s05, [
                'foo' => [
                    'qux' => new DummyArraySelection($f05, $s05, [
                        'cez' => 'unimportant',
                    ]),
                ],
                'bar' => new DummyArraySelection($f05, $s05, ['unimportant']),
            ]),
            'result' => new DummyArrayResult(false, [
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

        $f06 = new DummyArrayResultFactory();
        $s06 = self::getArrayObjectSelector();

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor' => [
                false,
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
            'array' => new DummyArraySelection($f06, $s06, [
                'foo' => [
                    'gez' => new DummyArraySelection($f06, $s06, [
                        'kik' => 'unimportant',
                    ]),
                    'qux' => new DummyArraySelection($f06, $s06, [
                        'cez' => 'unimportant',
                    ]),
                ],
                'bar' => new DummyArraySelection($f06, $s06, ['unimportant']),
            ]),
            'result' => new DummyArrayResult(false, [
                'foo' => [
                    'baz' => 'FOO.BAZ',
                    'qux' => new DummyArrayResult(false, [
                        'cez' => 'FOO.QUX.CEZ',
                    ]),
                    'gez' => new DummyArrayResult(false, [
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

        $f07a = new DummyArrayResultFactory('TAG-A');
        $f07e = new DummyArrayResultFactory('TAG-E');
        $s07a = self::getArrayObjectSelector();
        $s07e = self::getExceptionPropertySelector();

        $o06  = new \stdClass();

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'   => [false, $o06],
            'array' => new DummyArraySelection($f07e, $s07e, [
                'message'  => 'unimportant',
                'nonexist' => 'unimportant',
            ]),
            'result' => $o06,
        ];

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor'   => [false, new \Exception('foo', 123)],
            'array' => new DummyArraySelection($f07e, $s07e, [
                'message'  => 'unimportant',
                'nonexist' => 'unimportant',
            ]),
            'result' => new DummyArrayResult(false, [
                'message' => 'foo',
            ], 'TAG-E'),
        ];

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor' => [
                false,
                new \ArrayObject([
                    'f' => 'F',
                    'e' => new \Exception('foo', 123),
                    'd' => 'D',
                ]),
            ],
            'array' => new DummyArraySelection($f07a, $s07a, [
                'e' => new DummyArraySelection($f07e, $s07e, [
                    'message'  => 'unimportant',
                    'nonexist' => 'unimportant',
                ]),
                'f' => 'unimportant',
            ]),
            'result' => new DummyArrayResult(false, [
                'e' => new DummyArrayResult(false, [
                    'message' => 'foo',
                ], 'TAG-E'),
                'f' => 'F',
            ], 'TAG-A'),
        ];

        //
        // 08
        //

        $f08a = new DummyArrayResultFactory('TAG-A');
        $f08e = new DummyArrayResultFactory('TAG-E');
        $s08e = self::getExceptionPropertySelector();

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor' => [
                false,
                new \ArrayObject([
                    'f' => 'F',
                    'e' => new \Exception('foo', 123),
                    'd' => 'D',
                ]),
            ],
            'array' => new DummyArraySpec($f08a, [
                'e' => new DummyArraySelection($f08e, $s08e, [
                    'message'  => 'unimportant',
                    'nonexist' => 'unimportant',
                ]),
                'f' => 'unimportant',
            ]),
            'result' => new DummyArrayResult(false, [
                'f' => 'F',
                'e' => new DummyArrayResult(false, [
                    'message' => 'foo',
                ], 'TAG-E'),
                'd' => 'D',
            ], 'TAG-A'),
        ];

        //
        // 09
        //

        $f09a = new DummyArrayResultFactory('TAG-A', function ($input) {
            if ($input instanceof \Traversable) {
                $input = iterator_to_array($input);
            }
            ksort($input);
            return $input;
        });

        $f09e = new DummyArrayResultFactory('TAG-E');
        $s09e = self::getExceptionPropertySelector();

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor' => [
                false,
                new \ArrayObject([
                    'f' => 'F',
                    'e' => new \Exception('foo', 123),
                    'd' => 'D',
                ]),
            ],
            'array' => new DummyArraySpec($f09a, [
                'x' => 'UNIMPORTANT',
                'e' => new DummyArraySelection($f09e, $s09e, [
                    'message'  => 'unimportant',
                    'nonexist' => 'unimportant',
                    'code' => 'unimportant',
                ]),
                'f' => 'UNIMPORTANT',
            ]),
            'result' => new DummyArrayResult(false, [
                'd' => 'D',
                'e' => new DummyArrayResult(false, [
                    'message' => 'foo',
                    'code' => 123,
                ], 'TAG-E'),
                'f' => 'F',
            ], 'TAG-A'),
        ];

        //
        // 10
        //

        $f10a = new DummyArrayResultFactory('TAG-A');
        $s10a = self::getNamespaceVariableSelector([
            'ns1' => [
                'foo' => 'FOO',
                'bar' => 'BAR'
            ],
            'ns2' => [
                'baz' => 'BAZ',
                'gez' => new \ArrayObject([
                    'qux' => 'QUX',
                    'cop' => 'COP',
                ]),
            ],
        ]);

        $f10b = new DummyArrayResultFactory('TAG-B');
        $s10b = self::getArrayObjectSelector();

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor' => [false, ['cez' => 'CEZ']],
            'array' => new DummyArraySelection($f10a, $s10a, [
                'foo' => 'UNIMPORTANT',
                'baz' => 'UNIMPORTANT'
            ]),
            'result' => ['cez' => 'CEZ'],
        ];

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor' => [false, 'ns1'],
            'array' => new DummyArraySelection($f10a, $s10a, [
                'foo' => 'UNIMPORTANT',
                'baz' => 'UNIMPORTANT'
            ]),
            'result' => new DummyArrayResult(false, [
                'foo' => 'FOO'
            ], 'TAG-A'),
        ];

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor' => [false, 'ns2'],
            'array' => new DummyArraySelection($f10a, $s10a, [
                'foo' => 'UNIMPORTANT',
                'baz' => 'UNIMPORTANT'
            ]),
            'result' => new DummyArrayResult(false, [
                'baz' => 'BAZ'
            ], 'TAG-A'),
        ];

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor' => [false, 'ns2'],
            'array' => new DummyArraySelection($f10a, $s10a, [
                'gez' => new DummyArraySelection($f10b, $s10b, [
                    'cop' => 'UNIMPORTANT',
                    'qux' => [ 'UNIMPORTANT' ],
                    'fix' => [ 'UNIMPORTANT' ],
                ]),
                'baz' => 'UNIMPORTANT'
            ]),
            'result' => new DummyArrayResult(false, [
                'gez' => new DummyArrayResult(false, [
                    'cop' => 'COP',
                    'qux' => 'QUX',
                ], 'TAG-B'),
                'baz' => 'BAZ'
            ], 'TAG-A'),
        ];

        //
        // 11
        //

        $a11 = new  \ArrayObject([
            'foo' => 'FOO',
            'bar' => 'BAR',
        ]);

        $s11 = self::getArrayObjectSelector();

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor' => [false, $a11],
            'array' => new DummyArraySelectionOnly($s11, [
                'foo' => 'UNIMPORTANT',
                'baz' => 'UNIMPORTANT'
            ]),
            'result' => $a11,
        ];

        //
        // 12
        //

        $f12 = new DummyResultFactory(true);

        yield 'RecursiveResultFactoryVisitorTest.php:'.__LINE__ => [
            'ctor' => [false, 'FOO'],
            'array' => new DummyArraySpec($f12, [
                'foo' => 'UNIMPORTANT',
                'baz' => 'UNIMPORTANT'
            ]),
            'result' => 'FOO',
        ];

    }

    /**
     * @dataProvider provWithRecursiveTraversal
     *
     * @param mixed $result
     * @param array|\Traversable $array
     *
     * @psalm-param CtorArgs $ctor
     */
    public function testWithRecursiveTraversal(array $ctor, $array, $result): void
    {
        $visitor = new RecursiveResultFactoryVisitor(...$ctor);
        $traversal = new RecursiveTraversal();
        $traversal->walk($array, $visitor);

        $expect = $result;
        $actual = $visitor->result();

        $resultUnwrapper =RecursiveResultUnwrapper::create($ctor[0]);

        if ($expect instanceof ResultInterface) {
            $expect = $resultUnwrapper->unwrap($expect);
        }

        if ($actual instanceof ResultInterface) {
            $actual = $resultUnwrapper->unwrap($actual);
        }

        $this->assertSame($expect, $actual);
    }

    public function testMakeStackItemThrowsInternalError(): void
    {
        $visitor = new RecursiveResultFactoryVisitor(false, null);

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('$this->subjectResultCouple is null');

        $visitor->makeStackItem([], '', []);
    }

    public function testLeaveThrowsInternalError(): void
    {
        $visitor = new RecursiveResultFactoryVisitor(false, null);

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessage('$this->subjectResultCouple is null');

        $visitor->leave([], [], true);
    }

    private static function getArrayObjectSelector(): DummyValueSelector
    {
        return new DummyValueSelector(
            function ($subject): bool {
                return is_object($subject) && \ArrayObject::class === get_class($subject);
            },
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
        return new DummyValueSelector(function ($subject) use ($namespaces): bool {
            return is_string($subject) && array_key_exists($subject, $namespaces);
        }, function ($subject, $key, &$retval) use ($namespaces): bool {
            if (!is_string($subject) || !array_key_exists($subject, $namespaces)) {
                return false;
            }

            if (!array_key_exists($key, $namespaces[$subject])) {
                return false;
            }

            $retval = $namespaces[$subject][$key];
            return true;
        });
    }
}
// vim: syntax=php sw=4 ts=4 et:
