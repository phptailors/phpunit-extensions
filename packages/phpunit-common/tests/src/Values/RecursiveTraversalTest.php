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

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\RecursiveTraversal
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveTraversalTest extends TestCase
{
    public function testImplementsRecursiveTraversalInterface(): void
    {
        self::assertInstanceOf(RecursiveTraversalInterface::class, new RecursiveTraversal());
    }

    /**
     * @return iterable<string, array{values: ValuesInterface, visitor: DummyRecursiveVisitor, expect:mixed}>
     */
    public static function provWalk(): iterable
    {
        //
        // 01
        //

        $v01 = new ExpectedValues();

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'values'  => $v01,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'visit', 'node' => $v01, 'path' => []],
            ],
        ];

        //
        // 02
        //

        $v02 = new ActualValues();

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'values'  => $v02,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'visit', 'node' => $v02, 'path' => []],
            ],
        ];

        //
        // 03
        //

        $v03 = new ExpectedValues([
            'foo' => 'FOO',
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'values'  => $v03,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'visit', 'node' => $v03, 'path' => []],
                ['func' => 'visit', 'node' => $v03['foo'], 'path' => ['foo']],
            ],
        ];

        //
        // 04
        //

        $v04 = new ExpectedValues([
            'foo' => 'FOO',
            'bar' => 'BAR',
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'values'  => $v04,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'visit', 'node' => $v04, 'path' => []],
                ['func' => 'visit', 'node' => $v04['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v04['bar'], 'path' => ['bar']],
            ],
        ];

        //
        // 05
        //

        $v05 = new ExpectedValues([
            'bar' => 'BAR',
            'foo' => 'FOO',
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'values'  => $v05,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'visit', 'node' => $v05, 'path' => []],
                ['func' => 'visit', 'node' => $v05['bar'], 'path' => ['bar']],
                ['func' => 'visit', 'node' => $v05['foo'], 'path' => ['foo']],
            ],
        ];

        //
        // 06
        //

        $v06 = new ExpectedValues([
            'foo' => 'FOO',
            'bar' => 'BAR',
            'baz' => ['cor' => 'COR'],
        ]);
        $v06['baz']['qux'] = &$v06['baz'];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'values'  => $v06,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'visit', 'node' => $v06, 'path' => []],
                ['func' => 'visit', 'node' => $v06['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v06['bar'], 'path' => ['bar']],
                ['func' => 'visit', 'node' => $v06['baz'], 'path' => ['baz']],
                ['func' => 'visit', 'node' => $v06['baz']['cor'], 'path' => ['baz', 'cor']],
                ['func' => 'cycle', 'node' => $v06['baz']['qux'], 'path' => ['baz', 'qux']],
            ],
        ];

        //
        // 07
        //

        $v07 = new ExpectedValues([
            'foo' => 'FOO',
            'baz' => [],
            'bar' => 'BAR',
        ]);
        $v07['baz']['qux'] = &$v07['baz'];
        $v07['baz']['cor'] = 'COR';

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'values'  => $v07,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'visit', 'node' => $v07, 'path' => []],
                ['func' => 'visit', 'node' => $v07['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v07['baz'], 'path' => ['baz']],
                ['func' => 'cycle', 'node' => $v07['baz']['qux'], 'path' => ['baz', 'qux']],
                ['func' => 'visit', 'node' => $v07['baz']['cor'], 'path' => ['baz', 'cor']],
                ['func' => 'visit', 'node' => $v07['bar'], 'path' => ['bar']],
            ],
        ];

        //
        // 08
        //

        $v08 = new ExpectedValues([
            'foo' => 'FOO',
            'baz' => [],
            'bar' => 'BAR',
        ]);
        $v08['baz']['qux'] = &$v08;
        $v08['baz']['cor'] = 'COR';

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'values'  => $v08,
            'visitor' => new DummyRecursiveVisitor(function ($value, array $path) {
                return ['baz', 'qux', 'baz'] !== $path;
            }, true),
            'expect' => [
                ['func' => 'visit', 'node' => $v08, 'path' => []],
                ['func' => 'visit', 'node' => $v08['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v08['baz'], 'path' => ['baz']],
                ['func' => 'cycle', 'node' => $v08, 'path' => ['baz', 'qux']],
                ['func' => 'visit', 'node' => $v08, 'path' => ['baz', 'qux']],
                ['func' => 'visit', 'node' => $v08['foo'], 'path' => ['baz', 'qux', 'foo']],
                ['func' => 'visit', 'node' => $v08['baz'], 'path' => ['baz', 'qux', 'baz']],
                ['func' => 'visit', 'node' => $v08['bar'], 'path' => ['baz', 'qux', 'bar']],
                ['func' => 'visit', 'node' => $v08['baz']['cor'], 'path' => ['baz', 'cor']],
                ['func' => 'visit', 'node' => $v08['bar'], 'path' => ['bar']],
            ],
        ];

        //
        // 09
        //

        $v09 = new ExpectedValues([
            'foo' => ['baz' => 'BAZ'],
            'bar' => ['qux' => 'QUX'],
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'values'  => $v09,
            'visitor' => new DummyRecursiveVisitor(false),
            'expect'  => [
                ['func' => 'visit', 'node' => $v09, 'path' => []],
            ],
        ];

        //
        // 10
        //

        $v10 = new ExpectedValues([
            'foo' => ['baz' => 'BAZ'],
            'bar' => ['qux' => 'QUX'],
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'values'  => $v10,
            'visitor' => new DummyRecursiveVisitor(function ($value, array $path): bool {
                return count($path) < 1;
            }),
            'expect' => [
                ['func' => 'visit', 'node' => $v10, 'path' => []],
                ['func' => 'visit', 'node' => $v10['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v10['bar'], 'path' => ['bar']],
            ],
        ];

        //
        // 11
        //

        $v11 = new ExpectedValues([
            'foo' => ['baz' => ['cor' => 'FOO.BAZ.COR']],
            'bar' => ['qux' => ['cor' => 'BAR.QUX.COR']],
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'values'  => $v11,
            'visitor' => new DummyRecursiveVisitor(function ($value, array $path): bool {
                return count($path) < 2;
            }),
            'expect' => [
                ['func' => 'visit', 'node' => $v11, 'path' => []],
                ['func' => 'visit', 'node' => $v11['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v11['foo']['baz'], 'path' => ['foo', 'baz']],
                ['func' => 'visit', 'node' => $v11['bar'], 'path' => ['bar']],
                ['func' => 'visit', 'node' => $v11['bar']['qux'], 'path' => ['bar', 'qux']],
            ],
        ];
    }

    /**
     * @dataProvider provWalk
     *
     * @param mixed $expect
     */
    public function testWalk(ValuesInterface $values, DummyRecursiveVisitor $visitor, $expect): void
    {
        $traversal = new RecursiveTraversal();

        $traversal->walk($values, $visitor);

        $this->assertSame($expect, $visitor->trace());
    }
}
// vim: syntax=php sw=4 ts=4 et:
