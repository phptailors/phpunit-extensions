<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveMapper;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversal;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type ArrayMapper   = Closure(ArrayLike,list<RecursiveMapperStackItem>):bool
 * @psalm-type ValueMapper   = Closure(mixed,list<RecursiveMapperStackItem>):mixed
 * @psalm-type StackItem     = RecursiveMapperStackItem
 * @psalm-type BeginArgs     = list{0?:?ArrayMapper, 1?:?ValueMapper}
 * @psalm-type EnterTestCall = array{args: array{node: ArrayLike}, return: bool, next?: array-key}
 * @psalm-type CycleTestCall = array{args: array{node: ArrayLike, stack: list<StackItem>}, message: string}
 * @psalm-type VisitTestCall = array{args: array{node: mixed}, key?: array-key}
 * @psalm-type EndTestCall   = array{args: array{}}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
#[CoversClass(RecursiveMapperVisitor::class)]
#[CoversClass(RecursiveMapperVisitorTest::class)]
#[Small]
final class RecursiveMapperVisitorTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsRecursiveVisitorInterface(): void
    {
        self::assertInstanceOf(RecursiveVisitorInterface::class, new RecursiveMapperVisitor());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testInitialResult(): void
    {
        $visitor = new RecursiveMapperVisitor();
        $this->assertNull($visitor->result());
    }

    /**
     * @psalm-return iterable<string, array{
     *      call: CycleTestCall
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provCycle(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'call' => [
                'args'    => [
                    'node'  => [],
                    'stack' => [],
                ],
                'message' => 'Circular dependency found in nested array at $array.',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'call' => [
                'args'    => [
                    'node'  => [],
                    'stack' => [new RecursiveMapperStackItem([], 'foo', [])],
                ],
                'message' => 'Circular dependency found in nested array at $array[\'foo\'].',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'call' => [
                'args'    => [
                    'node'  => [],
                    'stack' => [
                        new RecursiveMapperStackItem([], 'foo', []),
                        new RecursiveMapperStackItem([], 'bar', []),
                    ],
                ],
                'message' => 'Circular dependency found in nested array at $array[\'foo\'][\'bar\'].',
            ],
        ];
    }

    /**
     * @psalm-param CycleTestCall $call
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provCycle')]
    public function testCycle(array $call): void
    {
        $visitor = new RecursiveMapperVisitor();

        $args = $call['args'];

        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessage($call['message']);

        $this->assertNull($visitor->cycle($args['node'], $args['stack']));
    }

    /**
     * @psalm-return iterable<string, array{
     *      begin: BeginArgs,
     *      calls: non-empty-list<EnterTestCall>,
     *      result: mixed
     *  }>
     *
     * @codeCoverageIgnore
     */
    public static function provEnterLeave(): iterable
    {
        //
        // 01
        //

        $calls01 = [
            [
                'args'   => [
                    'node' => [],
                ],
                'return' => true,
            ],
        ];

        $arrObj01 = new \ArrayObject([]);

        $arrMap01 =
            /**
             * @psalm-param ArrayLike $node
             */
            function (iterable &$node) use ($arrObj01): bool {
                if ([] === $node) {
                    $node = $arrObj01;
                }

                return true;
            };

        $valMap01 = (
            /**
             * @param mixed $value
             *
             * @return mixed
             */
            fn (mixed $value): mixed => $value
        );

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [],
            'calls'  => $calls01,
            'result' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$arrMap01],
            'calls'  => $calls01,
            'result' => $arrObj01,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [null, $valMap01],
            'calls'  => $calls01,
            'result' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$arrMap01, $valMap01],
            'calls'  => $calls01,
            'result' => $arrObj01,
        ];

        //
        // 02
        //

        $arrObj02 = new \ArrayObject([]);
        $itrObj02 = new \EmptyIterator();

        $calls02f = [
            [
                'args'   => [
                    'node' => $itrObj02,
                ],
                'return' => false,
            ],
        ];

        $calls02t = [
            [
                'args'   => [
                    'node' => $itrObj02,
                ],
                'return' => true,
            ],
        ];

        $arrMap02 =
            /**
             * @psalm-param ArrayLike $node
             */
            function (iterable &$node) use ($itrObj02, $arrObj02): bool {
                if ($itrObj02 === $node) {
                    $node = $arrObj02;
                }

                return true;
            };

        $valMap02 = (
            /**
             * @param mixed $value
             *
             * @return mixed
             */
            fn (mixed $value): mixed => $value
        );

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [],
            'calls'  => $calls02f,
            'result' => null,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$arrMap02],
            'calls'  => $calls02t,
            'result' => $arrObj02,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [],
            'calls'  => $calls02f,
            'result' => null,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$arrMap02, $valMap02],
            'calls'  => $calls02t,
            'result' => $arrObj02,
        ];

        //
        // 04
        //

        $s04 = [
            ['a' => 'A'],
            [],
            ['b' => 'B'],
        ];

        $calls04 = [
            [
                'args'   => [
                    'node' => $s04[0],
                ],
                'return' => true,
                'next'   => 'foo',
            ],
            [
                'args'   => [
                    'node' => $s04[1],
                ],
                'return' => true,
                'next'   => 'bar',
            ],
            [
                'args'   => [
                    'node' => $s04[2],
                ],
                'return' => true,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [],
            'calls'  => $calls04,
            'result' => [
                'a'   => 'A',
                'foo' => [
                    'bar' => [
                        'b' => 'B',
                    ],
                ],
            ],
        ];

        $arrMap04a =
            /**
             * @psalm-param ArrayLike $node
             */
            function (iterable &$node): bool {
                if (is_array($node)) {
                    $node = [];
                }

                return true;
            };

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$arrMap04a],
            'calls'  => $calls04,
            'result' => [
                'foo' => [
                    'bar' => [],
                ],
            ],
        ];

        //
        // 05
        //

        $s05 = [
            ['a' => 'A'],
            [],
            ['b' => 'B'],
        ];

        $calls05 = [
            [
                'args'   => [
                    'node' => $s05[0],
                ],
                'return' => true,
                'next'   => 'foo',
            ],
            [
                'args'   => [
                    'node' => $s05[1],
                ],
                'return' => true,
                'next'   => 'bar',
            ],
            [
                'args'   => [
                    'node' => $s05[2],
                ],
                'return' => false,
            ],
        ];

        $arrMap05a =
            /**
             * @psalm-param ArrayLike                      $node
             * @psalm-param list<RecursiveMapperStackItem> $stack
             */
            function (iterable &$node, array $stack): bool {
                if (is_array($node)) {
                    $node = [];
                }

                if (count($stack) > 1) {
                    return false;
                }

                return true;
            };

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$arrMap05a],
            'calls'  => $calls05,
            'result' => [
                'foo' => [],
            ],
        ];

        //
        // 06
        //

        $s06 = [
            ['a' => 'A'],
            [],
            ['b' => 'B'],
        ];

        $itrObj06 = new \EmptyIterator();

        $calls06 = [
            [
                'args'   => [
                    'node' => $s06[0],
                ],
                'return' => true,
                'next'   => 'foo',
            ],
            [
                'args'   => [
                    'node' => $s06[1],
                ],
                'return' => true,
                'next'   => 'bar',
            ],
            [
                'args'   => [
                    'node' => $s06[2],
                ],
                'return' => false,
            ],
        ];

        $arrMap06a =
            /**
             * @psalm-param ArrayLike                      $node
             * @psalm-param list<RecursiveMapperStackItem> $stack
             */
            function (iterable &$node, array $stack) use ($itrObj06): bool {
                if (is_array($node)) {
                    $node = [];
                }

                if (count($stack) > 1) {
                    $node = $itrObj06;
                }

                return true;
            };

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$arrMap06a],
            'calls'  => $calls06,
            'result' => [
                'foo' => [],
            ],
        ];

        // no more dumb tests, it's too convoluted...
    }

    /**
     * @param mixed $result
     *
     * @psalm-param BeginArgs                     $begin
     * @psalm-param non-empty-list<EnterTestCall> $calls
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provEnterLeave')]
    public function testEnterLeave(array $begin, array $calls, $result): void
    {
        $visitor = new RecursiveMapperVisitor();
        $stack = [];

        $visitor->begin(...$begin);

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
                /**
                 * @see https://github.com/sebastianbergmann/phpunit/issues/6199
                 *
                 * @psalm-var non-empty-list<StackItem> $stack
                 */
                $this->assertNotEmpty($stack);
                $visitor->freeStackItem(array_pop($stack), $stack);
            }
            $this->assertNull($visitor->leave($node, $stack, $call['return']));
        }

        $visitor->end();

        $this->assertSame($result, $visitor->result());
    }

    /**
     * @psalm-return iterable<string, array{
     *      begin: BeginArgs,
     *      root: ArrayLike,
     *      iter: bool,
     *      calls: non-empty-list<VisitTestCall>,
     *      result: mixed
     *  }>
     *
     * @codeCoverageIgnore
     */
    public static function provVisit(): iterable
    {
        //
        // 01
        //

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [],
            'root'   => [],
            'iter'   => false,
            'calls'  => [
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

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [],
            'root'   => [],
            'iter'   => true,
            'calls'  => [
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

        $arrObj02 = new \ArrayObject(['gez' => 'GEZ']);

        $valMap02 = (
            /**
             * @param mixed $value
             *
             * @return mixed
             */
            fn (mixed $value): mixed => ['gez' => 'GEZ'] === $value ? $arrObj02 : $value
        );

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [null, $valMap02],
            'root'   => [],
            'iter'   => true,
            'calls'  => [
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
                'bar' => $arrObj02,
            ],
        ];
    }

    /**
     * @param mixed $result
     *
     * @psalm-param BeginArgs                     $begin
     * @psalm-param non-empty-list<VisitTestCall> $calls
     * @psalm-param ArrayLike                     $root
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provVisit')]
    public function testVisit(array $begin, iterable $root, bool $iter, array $calls, $result): void
    {
        $visitor = new RecursiveMapperVisitor();
        $stack = [];

        $visitor->begin(...$begin);

        $i = 0;
        $this->assertTrue($visitor->enter($root, $stack));
        foreach ($calls as $call) {
            $args = $call['args'];

            /** @psalm-var mixed */
            $node = $args['node'];

            if ($iter) {
                if (!array_key_exists('key', $call)) {
                    // @codeCoverageIgnoreStart
                    // Reached only when the data provider is invalid.
                    throw new InvalidArgumentException("Invalid data provided, missing \$calls[{$i}]['key'].");
                    // @codeCoverageIgnoreEnd
                }
                array_push($stack, $visitor->makeStackItem($root, $call['key'], $stack));
            }

            $this->assertNull($visitor->visit($node, $stack, $iter));

            if ($iter) {
                /**
                 * @see https://github.com/sebastianbergmann/phpunit/issues/6199
                 *
                 * @psalm-var non-empty-list<StackItem> $stack
                 */
                $this->assertNotEmpty($stack);
                $visitor->freeStackItem(array_pop($stack), $stack);
            }
            ++$i;
        }
        $visitor->leave($root, $stack, $iter);

        $visitor->end();

        $this->assertSame($result, $visitor->result());
    }

    /**
     * @psalm-return iterable<string, array{
     *      begin: BeginArgs,
     *      array: ArrayLike,
     *      result: mixed
     *  }>
     *
     * @codeCoverageIgnore
     */
    public static function provMapAcyclic(): iterable
    {
        $arrayObject = [
            '[]'           => new \ArrayObject([]),
            '[foo => FOO]' => new \ArrayObject(['foo' => 'FOO']),
            '[baz => BAZ]' => new \ArrayObject(['baz' => 'BAZ']),
        ];

        $toArray =
            /**
             * @psalm-param ArrayLike $array
             */
            function (iterable &$array): bool {
                $array = (array) $array;

                return true;
            };

        $toLower =
            /**
             * @param mixed $value
             *
             * @return mixed
             */
            function (mixed $value): mixed {
                if (is_string($value)) {
                    return strtolower($value);
                }

                return $value;
            };

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [],
            'array'  => $arrayObject['[]'],
            'result' => $arrayObject['[]'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [null],
            'array'  => $arrayObject['[]'],
            'result' => $arrayObject['[]'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [null],
            'array'  => $arrayObject['[]'],
            'result' => $arrayObject['[]'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$toArray],
            'array'  => $arrayObject['[]'],
            'result' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [],
            'array'  => [
                'bar' => $arrayObject['[foo => FOO]'],
            ],
            'result' => [
                'bar' => $arrayObject['[foo => FOO]'],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$toArray],
            'array'  => [
                'bar' => $arrayObject['[foo => FOO]'],
            ],
            'result' => [
                'bar' => ['foo' => 'FOO'],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$toArray, $toLower],
            'array'  => [
                'bar' => $arrayObject['[foo => FOO]'],
            ],
            'result' => [
                'bar' => ['foo' => 'foo'],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'  => [$toArray, $toLower],
            'array'  => new \ArrayObject([
                'foo' => 'FOO',
                'bar' => new \ArrayObject([
                    'baz' => 'BAZ',
                    'qux' => 'QUX',
                ]),
            ]),
            'result' => [
                'foo' => 'foo',
                'bar' => [
                    'baz' => 'baz',
                    'qux' => 'qux',
                ],
            ],
        ];
    }

    /**
     * @param mixed $result
     *
     * @psalm-param BeginArgs $begin
     * @psalm-param ArrayLike $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provMapAcyclic')]
    public function testMapAcyclic(array $begin, iterable $array, $result): void
    {
        $traversal = new RecursiveTraversal();
        $visitor = new RecursiveMapperVisitor();

        $visitor->begin(...$begin);
        $traversal->walk($array, $visitor);
        $visitor->end();

        self::assertSame($result, $visitor->result());
    }

    /**
     * @psalm-return iterable<string, array{
     *      begin: BeginArgs,
     *      array: ArrayLike,
     *      message: string
     *  }>
     *
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArrayAssignment
     * @psalm-suppress MixedArrayAccess
     *
     * @codeCoverageIgnore
     */
    public static function provMapCyclic(): iterable
    {
        //
        // 01
        //

        /** @psalm-var \ArrayObject<array-key, mixed> */
        $v01 = new \ArrayObject([
            'foo' => [
                'bar' => [],
            ],
        ]);
        $v01['foo']['bar']['baz'] = $v01;

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'   => [fn (): bool => true],
            'array'   => $v01,
            'message' => 'Circular dependency found in nested array at $array[\'foo\'][\'bar\'][\'baz\'].',
        ];

        //
        // 02
        //

        /** @psalm-var \ArrayObject<array-key, mixed> */
        $v02 = new \ArrayObject([
            'foo' => [
                'bar' => new \ArrayObject([
                    'baz' => 'BAZ',
                ]),
            ],
        ]);
        $v02['foo']['bar']['qux'] = $v02['foo']['bar'];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'   => [fn (): bool => true],
            'array'   => $v02,
            'message' => 'Circular dependency found in nested array at $array[\'foo\'][\'bar\'][\'qux\'].',
        ];

        //
        // 03
        //

        /** @psalm-var \ArrayObject<array-key, mixed> */
        $v03 = new \ArrayObject([
            'foo' => [
                'bar' => new \ArrayObject([]),
                'baz' => new \ArrayObject([]),
            ],
        ]);
        $v03['foo']['bar']['qux'] = $v03['foo']['baz'];
        $v03['foo']['baz']['gez'] = $v03['foo']['bar'];

        yield basename(__FILE__).':'.__LINE__ => [
            'begin'   => [fn (): bool => true],
            'array'   => $v03,
            'message' => 'Circular dependency found in nested array at $array[\'foo\'][\'bar\'][\'qux\'][\'gez\'].',
        ];
    }

    /**
     * @psalm-param BeginArgs $begin
     * @psalm-param ArrayLike $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provMapCyclic')]
    public function testMapCyclic(array $begin, iterable &$array, string $message): void
    {
        $traversal = new RecursiveTraversal();
        $visitor = new RecursiveMapperVisitor();

        $visitor->begin(...$begin);

        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessage($message);

        $traversal->walk($array, $visitor);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMapWithCicrularArray(): void
    {
        // Couldn't pass circular arrays from data provider -- it SEGFAULTed on certain platforms.
        // That's why we have this case in a separate test function.
        $array = [
            'foo' => [],
        ];
        $array['foo']['bar'] = &$array;

        $traversal = new RecursiveTraversal();
        $visitor = new RecursiveMapperVisitor();

        $visitor->begin();

        $message = 'Circular dependency found in nested array at $array[\'foo\'][\'bar\'].';
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessage($message);

        $traversal->walk($array, $visitor);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testVisitThrowsInvalidArgumentException(): void
    {
        $visitor = new RecursiveMapperVisitor();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Argument 2 passed to '.RecursiveMapperVisitor::class.'::set() must be an iterable, string given.');

        $visitor->visit('xxx', [], false);
    }
}
// vim: syntax=php sw=4 ts=4 et:
