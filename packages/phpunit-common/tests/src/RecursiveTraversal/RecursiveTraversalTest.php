<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveTraversal;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\RecursiveVisitor\DummyRecursiveVisitor;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversal
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 * @psalm-type StackItem = DummyRecursiveVisitorStackItem
 */
final class RecursiveTraversalTest extends TestCase
{
    public function testImplementsRecursiveTraversalInterface(): void
    {
        /** @psalm-suppress MissingThrowsDocblock */
        self::assertInstanceOf(RecursiveTraversalInterface::class, new RecursiveTraversal());
    }

    /**
     * @psalm-return \Generator<non-falsy-string, array{
     *      array: ArrayLike,
     *      visitor: DummyRecursiveVisitor,
     *      expect: mixed
     *      }>
     *
     * @psalm-suppress PossiblyInvalidArrayOffset
     * @psalm-suppress PossiblyUndefinedArrayOffset
     * @psalm-suppress MixedArrayAccess
     * @psalm-suppress MixedArrayAssignment
     * @psalm-suppress InvalidArgument
     */
    public static function provWalk(): iterable
    {
        //
        // 00
        //

        $a00 = [];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a00,
            'visitor' => new DummyRecursiveVisitor(true),
            'expect'  => [
                ['func' => 'enter', 'node' => $a00, 'path' => []],
                ['func' => 'leave', 'node' => $a00, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a00,
            'visitor' => new DummyRecursiveVisitor(false),
            'expect'  => [
                ['func' => 'enter', 'node' => $a00, 'path' => []],
                ['func' => 'visit', 'node' => $a00, 'path' => []],
                ['func' => 'leave', 'node' => $a00, 'path' => []],
            ],
        ];

        //
        // 01
        //

        $a01 = new \ArrayObject();

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a01,
            'visitor' => new DummyRecursiveVisitor(true),
            'expect'  => [
                ['func' => 'enter', 'node' => $a01, 'path' => []],
                ['func' => 'leave', 'node' => $a01, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a01,
            'visitor' => new DummyRecursiveVisitor(false),
            'expect'  => [
                ['func' => 'enter', 'node' => $a01, 'path' => []],
                ['func' => 'visit', 'node' => $a01, 'path' => []],
                ['func' => 'leave', 'node' => $a01, 'path' => []],
            ],
        ];

        //
        // 03
        //

        $a03 = new \ArrayObject([
            'foo' => 'FOO',
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a03,
            'visitor' => new DummyRecursiveVisitor(true),
            'expect'  => [
                ['func' => 'enter', 'node' => $a03, 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a03, 'key' => 'foo', 'path' => []],
                ['func' => 'visit', 'node' => $a03['foo'], 'path' => ['foo']],
                ['func' => 'freeStackItem', 'node' => $a03, 'key' => 'foo', 'path' => []],
                ['func' => 'leave', 'node' => $a03, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a03,
            'visitor' => new DummyRecursiveVisitor(false),
            'expect'  => [
                ['func' => 'enter', 'node' => $a03, 'path' => []],
                ['func' => 'visit', 'node' => $a03, 'path' => []],
                ['func' => 'leave', 'node' => $a03, 'path' => []],
            ],
        ];

        //
        // 04
        //

        $a04 = new \ArrayObject([
            'foo' => 'FOO',
            'bar' => 'BAR',
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a04,
            'visitor' => new DummyRecursiveVisitor(true),
            'expect'  => [
                ['func' => 'enter', 'node' => $a04, 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a04, 'key' => 'foo', 'path' => []],
                ['func' => 'visit', 'node' => $a04['foo'], 'path' => ['foo']],
                ['func' => 'freeStackItem', 'node' => $a04, 'key' => 'foo', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a04, 'key' => 'bar', 'path' => []],
                ['func' => 'visit', 'node' => $a04['bar'], 'path' => ['bar']],
                ['func' => 'freeStackItem', 'node' => $a04, 'key' => 'bar', 'path' => []],
                ['func' => 'leave', 'node' => $a04, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a04,
            'visitor' => new DummyRecursiveVisitor(false),
            'expect'  => [
                ['func' => 'enter', 'node' => $a04, 'path' => []],
                ['func' => 'visit', 'node' => $a04, 'path' => []],
                ['func' => 'leave', 'node' => $a04, 'path' => []],
            ],
        ];

        //
        // 06
        //

        $a06 = new \ArrayObject([
            'foo' => 'FOO',
            'bar' => 'BAR',
            'baz' => ['cor' => 'COR'],
        ]);
        $a06['baz']['qux'] = &$a06['baz'];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a06,
            'visitor' => new DummyRecursiveVisitor(true),
            'expect'  => [
                ['func' => 'enter', 'node' => $a06, 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a06, 'key' => 'foo', 'path' => []],
                ['func' => 'visit', 'node' => $a06['foo'], 'path' => ['foo']],
                ['func' => 'freeStackItem', 'node' => $a06, 'key' => 'foo', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a06, 'key' => 'bar', 'path' => []],
                ['func' => 'visit', 'node' => $a06['bar'], 'path' => ['bar']],
                ['func' => 'freeStackItem', 'node' => $a06, 'key' => 'bar', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a06, 'key' => 'baz', 'path' => []],
                ['func' => 'enter', 'node' => $a06['baz'], 'path' => ['baz']],
                ['func' => 'makeStackItem', 'node' => $a06['baz'], 'key' => 'cor', 'path' => ['baz']],
                ['func' => 'visit', 'node' => $a06['baz']['cor'], 'path' => ['baz', 'cor']],
                ['func' => 'freeStackItem', 'node' => $a06['baz'], 'key' => 'cor', 'path' => ['baz']],
                ['func' => 'makeStackItem', 'node' => $a06['baz'], 'key' => 'qux', 'path' => ['baz']],
                ['func' => 'cycle', 'node' => $a06['baz']['qux'], 'path' => ['baz', 'qux']],
                ['func' => 'freeStackItem', 'node' => $a06['baz'], 'key' => 'qux', 'path' => ['baz']],
                ['func' => 'leave', 'node' => $a06['baz'], 'path' => ['baz']],
                ['func' => 'freeStackItem', 'node' => $a06, 'key' => 'baz', 'path' => []],
                ['func' => 'leave', 'node' => $a06, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a06,
            'visitor' => new DummyRecursiveVisitor(false),
            'expect'  => [
                ['func' => 'enter', 'node' => $a06, 'path' => []],
                ['func' => 'visit', 'node' => $a06, 'path' => []],
                ['func' => 'leave', 'node' => $a06, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a06,
            'visitor' => new DummyRecursiveVisitor(function ($node, array $stack): bool {
                return count($stack) < 1;
            }),
            'expect'  => [
                ['func' => 'enter', 'node' => $a06, 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a06, 'key' => 'foo', 'path' => []],
                ['func' => 'visit', 'node' => $a06['foo'], 'path' => ['foo']],
                ['func' => 'freeStackItem', 'node' => $a06, 'key' => 'foo', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a06, 'key' => 'bar', 'path' => []],
                ['func' => 'visit', 'node' => $a06['bar'], 'path' => ['bar']],
                ['func' => 'freeStackItem', 'node' => $a06, 'key' => 'bar', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a06, 'key' => 'baz', 'path' => []],
                ['func' => 'enter', 'node' => $a06['baz'], 'path' => ['baz']],
                ['func' => 'visit', 'node' => $a06['baz'], 'path' => ['baz']],
                ['func' => 'leave', 'node' => $a06['baz'], 'path' => ['baz']],
                ['func' => 'freeStackItem', 'node' => $a06, 'key' => 'baz', 'path' => []],
                ['func' => 'leave', 'node' => $a06, 'path' => []],
            ],
        ];

        //
        // 07
        //

        $a07 = new \ArrayObject([
            'foo' => 'FOO',
            'baz' => [],
            'bar' => 'BAR',
        ]);
        $a07['baz']['qux'] = &$a07['baz'];
        $a07['baz']['cor'] = 'COR';

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a07,
            'visitor' => new DummyRecursiveVisitor(true),
            'expect'  => [
                ['func' => 'enter', 'node' => $a07, 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a07, 'key' => 'foo', 'path' => []],
                ['func' => 'visit', 'node' => $a07['foo'], 'path' => ['foo']],
                ['func' => 'freeStackItem', 'node' => $a07, 'key' => 'foo', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a07, 'key' => 'baz', 'path' => []],
                ['func' => 'enter', 'node' => $a07['baz'], 'path' => ['baz']],
                ['func' => 'makeStackItem', 'node' => $a07['baz'], 'key' => 'qux', 'path' => ['baz']],
                ['func' => 'cycle', 'node' => $a07['baz']['qux'], 'path' => ['baz', 'qux']],
                ['func' => 'freeStackItem', 'node' => $a07['baz'], 'key' => 'qux', 'path' => ['baz']],
                ['func' => 'makeStackItem', 'node' => $a07['baz'], 'key' => 'cor', 'path' => ['baz']],
                ['func' => 'visit', 'node' => $a07['baz']['cor'], 'path' => ['baz', 'cor']],
                ['func' => 'freeStackItem', 'node' => $a07['baz'], 'key' => 'cor', 'path' => ['baz']],
                ['func' => 'leave', 'node' => $a07['baz'], 'path' => ['baz']],
                ['func' => 'freeStackItem', 'node' => $a07, 'key' => 'baz', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a07, 'key' => 'bar', 'path' => []],
                ['func' => 'visit', 'node' => $a07['bar'], 'path' => ['bar']],
                ['func' => 'freeStackItem', 'node' => $a07, 'key' => 'bar', 'path' => []],
                ['func' => 'leave', 'node' => $a07, 'path' => []],
            ],
        ];

        //
        // 08
        //

        $a08 = new \ArrayObject([
            'foo' => 'FOO',
            'baz' => [],
            'bar' => 'BAR',
        ]);
        $a08['baz']['qux'] = &$a08;
        $a08['baz']['cor'] = 'COR';

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a08,
            'visitor' => new DummyRecursiveVisitor(function ($value, array $stack) {
                return ['baz', 'qux', 'baz'] !== array_map(function ($item) { return $item->key(); }, $stack);
            }, true),
            'expect' => [
                ['func' => 'enter', 'node' => $a08, 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a08, 'key' => 'foo', 'path' => []],
                ['func' => 'visit', 'node' => $a08['foo'], 'path' => ['foo']],
                ['func' => 'freeStackItem', 'node' => $a08, 'key' => 'foo', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a08, 'key' => 'baz', 'path' => []],
                ['func' => 'enter', 'node' => $a08['baz'], 'path' => ['baz']],
                ['func' => 'makeStackItem', 'node' => $a08['baz'], 'key' => 'qux', 'path' => ['baz']],
                ['func' => 'cycle', 'node' => $a08, 'path' => ['baz', 'qux']],
                ['func' => 'enter', 'node' => $a08, 'path' => ['baz', 'qux']],
                ['func' => 'makeStackItem', 'node' => $a08['baz']['qux'], 'key' => 'foo', 'path' => ['baz', 'qux']],
                ['func' => 'visit', 'node' => $a08['foo'], 'path' => ['baz', 'qux', 'foo']],
                ['func' => 'freeStackItem', 'node' => $a08['baz']['qux'], 'key' => 'foo', 'path' => ['baz', 'qux']],
                ['func' => 'makeStackItem', 'node' => $a08['baz']['qux'], 'key' => 'baz', 'path' => ['baz', 'qux']],
                ['func' => 'cycle', 'node' => $a08['baz'], 'path' => ['baz', 'qux', 'baz']],
                ['func' => 'enter', 'node' => $a08['baz'], 'path' => ['baz', 'qux', 'baz']],
                ['func' => 'visit', 'node' => $a08['baz'], 'path' => ['baz', 'qux', 'baz']],
                ['func' => 'leave', 'node' => $a08['baz'], 'path' => ['baz', 'qux', 'baz']],
                ['func' => 'freeStackItem', 'node' => $a08['baz']['qux'], 'key' => 'baz', 'path' => ['baz', 'qux']],
                ['func' => 'makeStackItem', 'node' => $a08['baz']['qux'], 'key' => 'bar', 'path' => ['baz', 'qux']],
                ['func' => 'visit', 'node' => $a08['bar'], 'path' => ['baz', 'qux', 'bar']],
                ['func' => 'freeStackItem', 'node' => $a08['baz']['qux'], 'key' => 'bar', 'path' => ['baz', 'qux']],
                ['func' => 'leave', 'node' => $a08, 'path' => ['baz', 'qux']],
                ['func' => 'freeStackItem', 'node' => $a08['baz'], 'key' => 'qux', 'path' => ['baz']],
                ['func' => 'makeStackItem', 'node' => $a08['baz'], 'key' => 'cor', 'path' => ['baz']],
                ['func' => 'visit', 'node' => $a08['baz']['cor'], 'path' => ['baz', 'cor']],
                ['func' => 'freeStackItem', 'node' => $a08['baz'], 'key' => 'cor', 'path' => ['baz']],
                ['func' => 'leave', 'node' => $a08['baz'], 'path' => ['baz']],
                ['func' => 'freeStackItem', 'node' => $a08, 'key' => 'baz', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a08, 'key' => 'bar', 'path' => []],
                ['func' => 'visit', 'node' => $a08['bar'], 'path' => ['bar']],
                ['func' => 'freeStackItem', 'node' => $a08, 'key' => 'bar', 'path' => []],
                ['func' => 'leave', 'node' => $a08, 'path' => []],
            ],
        ];

        //
        // 09
        //

        $a09 = new \ArrayObject([
            'foo' => ['baz' => 'BAZ'],
            'bar' => ['qux' => 'QUX'],
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a09,
            'visitor' => new DummyRecursiveVisitor(function ($values, array $stack) {
                return count($stack) < 1;
            }),
            'expect' => [
                ['func' => 'enter', 'node' => $a09, 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a09, 'key' => 'foo', 'path' => []],
                ['func' => 'enter', 'node' => $a09['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $a09['foo'], 'path' => ['foo']],
                ['func' => 'leave', 'node' => $a09['foo'], 'path' => ['foo']],
                ['func' => 'freeStackItem', 'node' => $a09, 'key' => 'foo', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a09, 'key' => 'bar', 'path' => []],
                ['func' => 'enter', 'node' => $a09['bar'], 'path' => ['bar']],
                ['func' => 'visit', 'node' => $a09['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $a09['bar'], 'path' => ['bar']],
                ['func' => 'freeStackItem', 'node' => $a09, 'key' => 'bar', 'path' => []],
                ['func' => 'leave', 'node' => $a09, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a09,
            'visitor' => new DummyRecursiveVisitor(true),
            'expect'  => [
                ['func' => 'enter', 'node' => $a09, 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a09, 'key' => 'foo', 'path' => []],
                ['func' => 'enter', 'node' => $a09['foo'], 'path' => ['foo']],
                ['func' => 'makeStackItem', 'node' => $a09['foo'], 'key' => 'baz', 'path' => ['foo']],
                ['func' => 'visit', 'node' => $a09['foo']['baz'], 'path' => ['foo', 'baz']],
                ['func' => 'freeStackItem', 'node' => $a09['foo'], 'key' => 'baz', 'path' => ['foo']],
                ['func' => 'leave', 'node' => $a09['foo'], 'path' => ['foo']],
                ['func' => 'freeStackItem', 'node' => $a09, 'key' => 'foo', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a09, 'key' => 'bar', 'path' => []],
                ['func' => 'enter', 'node' => $a09['bar'], 'path' => ['bar']],
                ['func' => 'makeStackItem', 'node' => $a09['bar'], 'key' => 'qux', 'path' => ['bar']],
                ['func' => 'visit', 'node' => $a09['bar']['qux'], 'path' => ['bar', 'qux']],
                ['func' => 'freeStackItem', 'node' => $a09['bar'], 'key' => 'qux', 'path' => ['bar']],
                ['func' => 'leave', 'node' => $a09['bar'], 'path' => ['bar']],
                ['func' => 'freeStackItem', 'node' => $a09, 'key' => 'bar', 'path' => []],
                ['func' => 'leave', 'node' => $a09, 'path' => []],
            ],
        ];

        //
        // 10
        //

        $a10 = new \ArrayObject([
            'foo' => ['baz' => 'BAZ', 'cor' => new \ArrayObject(['x' => 'X'])],
            'bar' => ['qux' => 'QUX', 'cor' => new \ArrayObject(['y' => 'Y'])],
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a10,
            'visitor' => new DummyRecursiveVisitor(function ($value, array $stack): bool {
                return count($stack) < 2;
            }),
            'expect' => [
                ['func' => 'enter', 'node' => $a10, 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a10, 'key' => 'foo', 'path' => []],
                ['func' => 'enter', 'node' => $a10['foo'], 'path' => ['foo']],
                ['func' => 'makeStackItem', 'node' => $a10['foo'], 'key' => 'baz', 'path' => ['foo']],
                ['func' => 'visit', 'node' => $a10['foo']['baz'], 'path' => ['foo', 'baz']],
                ['func' => 'freeStackItem', 'node' => $a10['foo'], 'key' => 'baz', 'path' => ['foo']],
                ['func' => 'makeStackItem', 'node' => $a10['foo'], 'key' => 'cor', 'path' => ['foo']],
                ['func' => 'enter', 'node' => $a10['foo']['cor'], 'path' => ['foo', 'cor']],
                ['func' => 'visit', 'node' => $a10['foo']['cor'], 'path' => ['foo', 'cor']],
                ['func' => 'leave', 'node' => $a10['foo']['cor'], 'path' => ['foo', 'cor']],
                ['func' => 'freeStackItem', 'node' => $a10['foo'], 'key' => 'cor', 'path' => ['foo']],
                ['func' => 'leave', 'node' => $a10['foo'], 'path' => ['foo']],
                ['func' => 'freeStackItem', 'node' => $a10, 'key' => 'foo', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a10, 'key' => 'bar', 'path' => []],
                ['func' => 'enter', 'node' => $a10['bar'], 'path' => ['bar']],
                ['func' => 'makeStackItem', 'node' => $a10['bar'], 'key' => 'qux', 'path' => ['bar']],
                ['func' => 'visit', 'node' => $a10['bar']['qux'], 'path' => ['bar', 'qux']],
                ['func' => 'freeStackItem', 'node' => $a10['bar'], 'key' => 'qux', 'path' => ['bar']],
                ['func' => 'makeStackItem', 'node' => $a10['bar'], 'key' => 'cor', 'path' => ['bar']],
                ['func' => 'enter', 'node' => $a10['bar']['cor'], 'path' => ['bar', 'cor']],
                ['func' => 'visit', 'node' => $a10['bar']['cor'], 'path' => ['bar', 'cor']],
                ['func' => 'leave', 'node' => $a10['bar']['cor'], 'path' => ['bar', 'cor']],
                ['func' => 'freeStackItem', 'node' => $a10['bar'], 'key' => 'cor', 'path' => ['bar']],
                ['func' => 'leave', 'node' => $a10['bar'], 'path' => ['bar']],
                ['func' => 'freeStackItem', 'node' => $a10, 'key' => 'bar', 'path' => []],
                ['func' => 'leave', 'node' => $a10, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a10,
            'visitor' => new DummyRecursiveVisitor(function ($value, array $stack): bool {
                return count($stack) < 1;
            }),
            'expect' => [
                ['func' => 'enter', 'node' => $a10, 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a10, 'key' => 'foo', 'path' => []],
                ['func' => 'enter', 'node' => $a10['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $a10['foo'], 'path' => ['foo']],
                ['func' => 'leave', 'node' => $a10['foo'], 'path' => ['foo']],
                ['func' => 'freeStackItem', 'node' => $a10, 'key' => 'foo', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a10, 'key' => 'bar', 'path' => []],
                ['func' => 'enter', 'node' => $a10['bar'], 'path' => ['bar']],
                ['func' => 'visit', 'node' => $a10['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $a10['bar'], 'path' => ['bar']],
                ['func' => 'freeStackItem', 'node' => $a10, 'key' => 'bar', 'path' => []],
                ['func' => 'leave', 'node' => $a10, 'path' => []],
            ],
        ];

        //
        // 11
        //

        $a11 = new \ArrayObject([
            'foo' => ['baz' => new \ArrayObject(['cor' => 'FOO.BAZ.COR'])],
            'bar' => ['qux' => new \ArrayObject(['cor' => 'BAR.QUX.COR'])],
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a11,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $a11, 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a11, 'key' => 'foo', 'path' => []],
                ['func' => 'enter', 'node' => $a11['foo'], 'path' => ['foo']],
                ['func' => 'makeStackItem', 'node' => $a11['foo'], 'key' => 'baz', 'path' => ['foo']],
                ['func' => 'enter', 'node' => $a11['foo']['baz'], 'path' => ['foo', 'baz']],
                ['func' => 'makeStackItem', 'node' => $a11['foo']['baz'], 'key' => 'cor', 'path' => ['foo', 'baz']],
                ['func' => 'visit', 'node' => $a11['foo']['baz']['cor'], 'path' => ['foo', 'baz', 'cor']],
                ['func' => 'freeStackItem', 'node' => $a11['foo']['baz'], 'key' => 'cor', 'path' => ['foo', 'baz']],
                ['func' => 'leave', 'node' => $a11['foo']['baz'], 'path' => ['foo', 'baz']],
                ['func' => 'freeStackItem', 'node' => $a11['foo'], 'key' => 'baz', 'path' => ['foo']],
                ['func' => 'leave', 'node' => $a11['foo'], 'path' => ['foo']],
                ['func' => 'freeStackItem', 'node' => $a11, 'key' => 'foo', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a11, 'key' => 'bar', 'path' => []],
                ['func' => 'enter', 'node' => $a11['bar'], 'path' => ['bar']],
                ['func' => 'makeStackItem', 'node' => $a11['bar'], 'key' => 'qux', 'path' => ['bar']],
                ['func' => 'enter', 'node' => $a11['bar']['qux'], 'path' => ['bar', 'qux']],
                ['func' => 'makeStackItem', 'node' => $a11['bar']['qux'], 'key' => 'cor', 'path' => ['bar', 'qux']],
                ['func' => 'visit', 'node' => $a11['bar']['qux']['cor'], 'path' => ['bar', 'qux', 'cor']],
                ['func' => 'freeStackItem', 'node' => $a11['bar']['qux'], 'key' => 'cor', 'path' => ['bar', 'qux']],
                ['func' => 'leave', 'node' => $a11['bar']['qux'], 'path' => ['bar', 'qux']],
                ['func' => 'freeStackItem', 'node' => $a11['bar'], 'key' => 'qux', 'path' => ['bar']],
                ['func' => 'leave', 'node' => $a11['bar'], 'path' => ['bar']],
                ['func' => 'freeStackItem', 'node' => $a11, 'key' => 'bar', 'path' => []],
                ['func' => 'leave', 'node' => $a11, 'path' => []],
            ],
        ];

        //
        // 12
        //

        $a12 = new \ArrayObject([
            'foo' => 'FOO',
            'bar' => 'BAR',
            'baz' => ['cor' => 'COR'],
        ]);
        $a12['baz']['qux'] = new \ArrayObject(['zot' => &$a12['baz']]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'array'  => $a12,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $a12, 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a12, 'key' => 'foo', 'path' => []],
                ['func' => 'visit', 'node' => $a12['foo'], 'path' => ['foo']],
                ['func' => 'freeStackItem', 'node' => $a12, 'key' => 'foo', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a12, 'key' => 'bar', 'path' => []],
                ['func' => 'visit', 'node' => $a12['bar'], 'path' => ['bar']],
                ['func' => 'freeStackItem', 'node' => $a12, 'key' => 'bar', 'path' => []],
                ['func' => 'makeStackItem', 'node' => $a12, 'key' => 'baz', 'path' => []],
                ['func' => 'enter', 'node' => $a12['baz'], 'path' => ['baz']],
                ['func' => 'makeStackItem', 'node' => $a12['baz'], 'key' => 'cor', 'path' => ['baz']],
                ['func' => 'visit', 'node' => $a12['baz']['cor'], 'path' => ['baz', 'cor']],
                ['func' => 'freeStackItem', 'node' => $a12['baz'], 'key' => 'cor', 'path' => ['baz']],
                ['func' => 'makeStackItem', 'node' => $a12['baz'], 'key' => 'qux', 'path' => ['baz']],
                ['func' => 'enter', 'node' => $a12['baz']['qux'], 'path' => ['baz', 'qux']],
                ['func' => 'makeStackItem', 'node' => $a12['baz']['qux'], 'key' => 'zot', 'path' => ['baz', 'qux']],
                ['func' => 'cycle', 'node' => $a12['baz']['qux']['zot'], 'path' => ['baz', 'qux', 'zot']],
                ['func' => 'freeStackItem', 'node' => $a12['baz']['qux'], 'key' => 'zot', 'path' => ['baz', 'qux']],
                ['func' => 'leave', 'node' => $a12['baz']['qux'], 'path' => ['baz', 'qux']],
                ['func' => 'freeStackItem', 'node' => $a12['baz'], 'key' => 'qux', 'path' => ['baz']],
                ['func' => 'leave', 'node' => $a12['baz'], 'path' => ['baz']],
                ['func' => 'freeStackItem', 'node' => $a12, 'key' => 'baz', 'path' => []],
                ['func' => 'leave', 'node' => $a12, 'path' => []],
            ],
        ];
    }

    /**
     * @dataProvider provWalk
     *
     * @param mixed $expect
     *
     * @psalm-param ArrayLike $array
     */
    public function testWalk(iterable $array, DummyRecursiveVisitor $visitor, $expect): void
    {
        $traversal = new RecursiveTraversal();

        $traversal->walk($array, $visitor);

        /** @psalm-suppress MissingThrowsDocblock */
        $this->assertSame($expect, $visitor->trace());
    }
}
// vim: syntax=php sw=4 ts=4 et:
