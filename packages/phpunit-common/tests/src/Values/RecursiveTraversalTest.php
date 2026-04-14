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
     * @return iterable<string, array{args: array, values: ValuesInterface, visitor: DummyRecursiveVisitor, expect:mixed}>
     */
    public static function provWalk(): iterable
    {
        //
        // 01
        //

        $v01 = new ExpectedValues();

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [],
            'values'  => $v01,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v01, 'path' => []],
                ['func' => 'leave', 'node' => $v01, 'path' => []],
            ],
        ];

        //
        // 02
        //

        $v02 = new ActualValues();

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [],
            'values'  => $v02,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v02, 'path' => []],
                ['func' => 'leave', 'node' => $v02, 'path' => []],
            ],
        ];

        //
        // 03
        //

        $v03 = new ExpectedValues([
            'foo' => 'FOO',
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [],
            'values'  => $v03,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v03, 'path' => []],
                ['func' => 'visit', 'node' => $v03['foo'], 'path' => ['foo']],
                ['func' => 'leave', 'node' => $v03, 'path' => []],
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
            'args'    => [],
            'values'  => $v04,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v04, 'path' => []],
                ['func' => 'visit', 'node' => $v04['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v04['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v04, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [],
            'values'  => $v04,
            'visitor' => new DummyRecursiveVisitor(false),
            'expect'  => [
                ['func' => 'enter', 'node' => $v04, 'path' => []],
                ['func' => 'leave', 'node' => $v04, 'path' => []],
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
            'args'    => [],
            'values'  => $v05,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v05, 'path' => []],
                ['func' => 'visit', 'node' => $v05['bar'], 'path' => ['bar']],
                ['func' => 'visit', 'node' => $v05['foo'], 'path' => ['foo']],
                ['func' => 'leave', 'node' => $v05, 'path' => []],
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
            'args'    => [],
            'values'  => $v06,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v06, 'path' => []],
                ['func' => 'visit', 'node' => $v06['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v06['bar'], 'path' => ['bar']],
                ['func' => 'enter', 'node' => $v06['baz'], 'path' => ['baz']],
                ['func' => 'visit', 'node' => $v06['baz']['cor'], 'path' => ['baz', 'cor']],
                ['func' => 'cycle', 'node' => $v06['baz']['qux'], 'path' => ['baz', 'qux']],
                ['func' => 'leave', 'node' => $v06['baz'], 'path' => ['baz']],
                ['func' => 'leave', 'node' => $v06, 'path' => []],
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
            'args'    => [],
            'values'  => $v07,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v07, 'path' => []],
                ['func' => 'visit', 'node' => $v07['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v07['baz'], 'path' => ['baz']],
                ['func' => 'cycle', 'node' => $v07['baz']['qux'], 'path' => ['baz', 'qux']],
                ['func' => 'visit', 'node' => $v07['baz']['cor'], 'path' => ['baz', 'cor']],
                ['func' => 'leave', 'node' => $v07['baz'], 'path' => ['baz']],
                ['func' => 'visit', 'node' => $v07['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v07, 'path' => []],
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
            'args'    => [],
            'values'  => $v08,
            'visitor' => new DummyRecursiveVisitor(
                fn (mixed $value, array $path): bool => ['baz', 'qux', 'baz'] !== $path,
                true
            ),
            'expect' => [
                ['func' => 'enter', 'node' => $v08, 'path' => []],
                ['func' => 'visit', 'node' => $v08['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v08['baz'], 'path' => ['baz']],
                ['func' => 'cycle', 'node' => $v08, 'path' => ['baz', 'qux']],
                ['func' => 'enter', 'node' => $v08, 'path' => ['baz', 'qux']],
                ['func' => 'visit', 'node' => $v08['foo'], 'path' => ['baz', 'qux', 'foo']],
                ['func' => 'cycle', 'node' => $v08['baz'], 'path' => ['baz', 'qux', 'baz']],
                ['func' => 'enter', 'node' => $v08['baz'], 'path' => ['baz', 'qux', 'baz']],
                ['func' => 'leave', 'node' => $v08['baz'], 'path' => ['baz', 'qux', 'baz']],
                ['func' => 'visit', 'node' => $v08['bar'], 'path' => ['baz', 'qux', 'bar']],
                ['func' => 'leave', 'node' => $v08, 'path' => ['baz', 'qux']],
                ['func' => 'visit', 'node' => $v08['baz']['cor'], 'path' => ['baz', 'cor']],
                ['func' => 'leave', 'node' => $v08['baz'], 'path' => ['baz']],
                ['func' => 'visit', 'node' => $v08['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v08, 'path' => []],
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
            'args'    => [],
            'values'  => $v09,
            'visitor' => new DummyRecursiveVisitor(false),
            'expect'  => [
                ['func' => 'enter', 'node' => $v09, 'path' => []],
                ['func' => 'leave', 'node' => $v09, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [],
            'values'  => $v09,
            'visitor' => new DummyRecursiveVisitor(fn (array|ValuesInterface $values, array $path): bool => (count($path) < 1)),
            'expect'  => [
                ['func' => 'enter', 'node' => $v09, 'path' => []],
                ['func' => 'enter', 'node' => $v09['foo'], 'path' => ['foo']],
                ['func' => 'leave', 'node' => $v09['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v09['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v09['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v09, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [],
            'values'  => $v09,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v09, 'path' => []],
                ['func' => 'enter', 'node' => $v09['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v09['foo']['baz'], 'path' => ['foo', 'baz']],
                ['func' => 'leave', 'node' => $v09['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v09['bar'], 'path' => ['bar']],
                ['func' => 'visit', 'node' => $v09['bar']['qux'], 'path' => ['bar', 'qux']],
                ['func' => 'leave', 'node' => $v09['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v09, 'path' => []],
            ],
        ];

        //
        // 10
        //

        $v10 = new ExpectedValues([
            'foo' => ['baz' => 'BAZ', 'cor' => new ExpectedValues(['x' => 'X'])],
            'bar' => ['qux' => 'QUX', 'cor' => new ExpectedValues(['y' => 'Y'])],
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [],
            'values'  => $v10,
            'visitor' => new DummyRecursiveVisitor(fn (mixed $value, array $path): bool => (count($path) < 2)),
            'expect'  => [
                ['func' => 'enter', 'node' => $v10, 'path' => []],
                ['func' => 'enter', 'node' => $v10['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v10['foo']['baz'], 'path' => ['foo', 'baz']],
                ['func' => 'enter', 'node' => $v10['foo']['cor'], 'path' => ['foo', 'cor']],
                ['func' => 'leave', 'node' => $v10['foo']['cor'], 'path' => ['foo', 'cor']],
                ['func' => 'leave', 'node' => $v10['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v10['bar'], 'path' => ['bar']],
                ['func' => 'visit', 'node' => $v10['bar']['qux'], 'path' => ['bar', 'qux']],
                ['func' => 'enter', 'node' => $v10['bar']['cor'], 'path' => ['bar', 'cor']],
                ['func' => 'leave', 'node' => $v10['bar']['cor'], 'path' => ['bar', 'cor']],
                ['func' => 'leave', 'node' => $v10['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v10, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [],
            'values'  => $v10,
            'visitor' => new DummyRecursiveVisitor(function ($value, array $path): bool {
                return count($path) < 1;
            }),
            'expect' => [
                ['func' => 'enter', 'node' => $v10, 'path' => []],
                ['func' => 'enter', 'node' => $v10['foo'], 'path' => ['foo']],
                ['func' => 'leave', 'node' => $v10['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v10['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v10['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v10, 'path' => []],
            ],
        ];

        //
        // 11
        //

        $v11 = new ExpectedValues([
            'foo' => ['baz' => new ExpectedValues(['cor' => 'FOO.BAZ.COR'])],
            'bar' => ['qux' => new ActualValues(['cor' => 'BAR.QUX.COR'])],
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [],
            'values'  => $v11,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v11, 'path' => []],
                ['func' => 'enter', 'node' => $v11['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v11['foo']['baz'], 'path' => ['foo', 'baz']],
                ['func' => 'visit', 'node' => $v11['foo']['baz']['cor'], 'path' => ['foo', 'baz', 'cor']],
                ['func' => 'leave', 'node' => $v11['foo']['baz'], 'path' => ['foo', 'baz']],
                ['func' => 'leave', 'node' => $v11['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v11['bar'], 'path' => ['bar']],
                ['func' => 'enter', 'node' => $v11['bar']['qux'], 'path' => ['bar', 'qux']],
                ['func' => 'visit', 'node' => $v11['bar']['qux']['cor'], 'path' => ['bar', 'qux', 'cor']],
                ['func' => 'leave', 'node' => $v11['bar']['qux'], 'path' => ['bar', 'qux']],
                ['func' => 'leave', 'node' => $v11['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v11, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [false, true],
            'values'  => $v11,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v11, 'path' => []],
                ['func' => 'enter', 'node' => $v11['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v11['foo']['baz'], 'path' => ['foo', 'baz']],
                ['func' => 'leave', 'node' => $v11['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v11['bar'], 'path' => ['bar']],
                ['func' => 'visit', 'node' => $v11['bar']['qux'], 'path' => ['bar', 'qux']],
                ['func' => 'leave', 'node' => $v11['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v11, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [true, true],
            'values'  => $v11,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v11, 'path' => []],
                ['func' => 'enter', 'node' => $v11['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v11['foo']['baz'], 'path' => ['foo', 'baz']],
                ['func' => 'leave', 'node' => $v11['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v11['bar'], 'path' => ['bar']],
                ['func' => 'visit', 'node' => $v11['bar']['qux'], 'path' => ['bar', 'qux']],
                ['func' => 'leave', 'node' => $v11['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v11, 'path' => []],
            ],
        ];

        //
        // 12
        //
        $v12baz = new ExpectedValues(['cor' => 'FOO.BAZ.COR']);
        $v12qux = new ActualValues(['cor' => 'BAR.QUX.COR']);
        $v12 = new ExpectedValues([
            'foo' => ['baz' => new DummyValuesWrapper($v12baz)],
            'bar' => ['qux' => new DummyValuesWrapper($v12qux)],
        ]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [],
            'values'  => $v12,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v12, 'path' => []],
                ['func' => 'enter', 'node' => $v12['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v12baz, 'path' => ['foo', 'baz']],
                ['func' => 'visit', 'node' => $v12baz['cor'], 'path' => ['foo', 'baz', 'cor']],
                ['func' => 'leave', 'node' => $v12baz, 'path' => ['foo', 'baz']],
                ['func' => 'leave', 'node' => $v12['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v12['bar'], 'path' => ['bar']],
                ['func' => 'enter', 'node' => $v12qux, 'path' => ['bar', 'qux']],
                ['func' => 'visit', 'node' => $v12qux['cor'], 'path' => ['bar', 'qux', 'cor']],
                ['func' => 'leave', 'node' => $v12qux, 'path' => ['bar', 'qux']],
                ['func' => 'leave', 'node' => $v12['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v12, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [false],
            'values'  => $v12,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v12, 'path' => []],
                ['func' => 'enter', 'node' => $v12['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v12baz, 'path' => ['foo', 'baz']],
                ['func' => 'visit', 'node' => $v12baz['cor'], 'path' => ['foo', 'baz', 'cor']],
                ['func' => 'leave', 'node' => $v12baz, 'path' => ['foo', 'baz']],
                ['func' => 'leave', 'node' => $v12['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v12['bar'], 'path' => ['bar']],
                ['func' => 'enter', 'node' => $v12qux, 'path' => ['bar', 'qux']],
                ['func' => 'visit', 'node' => $v12qux['cor'], 'path' => ['bar', 'qux', 'cor']],
                ['func' => 'leave', 'node' => $v12qux, 'path' => ['bar', 'qux']],
                ['func' => 'leave', 'node' => $v12['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v12, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [false, false],
            'values'  => $v12,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v12, 'path' => []],
                ['func' => 'enter', 'node' => $v12['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v12baz, 'path' => ['foo', 'baz']],
                ['func' => 'visit', 'node' => $v12baz['cor'], 'path' => ['foo', 'baz', 'cor']],
                ['func' => 'leave', 'node' => $v12baz, 'path' => ['foo', 'baz']],
                ['func' => 'leave', 'node' => $v12['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v12['bar'], 'path' => ['bar']],
                ['func' => 'enter', 'node' => $v12qux, 'path' => ['bar', 'qux']],
                ['func' => 'visit', 'node' => $v12qux['cor'], 'path' => ['bar', 'qux', 'cor']],
                ['func' => 'leave', 'node' => $v12qux, 'path' => ['bar', 'qux']],
                ['func' => 'leave', 'node' => $v12['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v12, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [false, true],
            'values'  => $v12,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v12, 'path' => []],
                ['func' => 'enter', 'node' => $v12['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v12baz, 'path' => ['foo', 'baz']],
                ['func' => 'leave', 'node' => $v12['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v12['bar'], 'path' => ['bar']],
                ['func' => 'visit', 'node' => $v12qux, 'path' => ['bar', 'qux']],
                ['func' => 'leave', 'node' => $v12['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v12, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [true, false],
            'values'  => $v12,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v12, 'path' => []],
                ['func' => 'enter', 'node' => $v12['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v12['foo']['baz'], 'path' => ['foo', 'baz']],
                ['func' => 'leave', 'node' => $v12['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v12['bar'], 'path' => ['bar']],
                ['func' => 'visit', 'node' => $v12['bar']['qux'], 'path' => ['bar', 'qux']],
                ['func' => 'leave', 'node' => $v12['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v12, 'path' => []],
            ],
        ];

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [true, true],
            'values'  => $v12,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v12, 'path' => []],
                ['func' => 'enter', 'node' => $v12['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v12['foo']['baz'], 'path' => ['foo', 'baz']],
                ['func' => 'leave', 'node' => $v12['foo'], 'path' => ['foo']],
                ['func' => 'enter', 'node' => $v12['bar'], 'path' => ['bar']],
                ['func' => 'visit', 'node' => $v12['bar']['qux'], 'path' => ['bar', 'qux']],
                ['func' => 'leave', 'node' => $v12['bar'], 'path' => ['bar']],
                ['func' => 'leave', 'node' => $v12, 'path' => []],
            ],
        ];

        //
        // 13
        //

        $v13 = new ExpectedValues([
            'foo' => 'FOO',
            'bar' => 'BAR',
            'baz' => ['cor' => 'COR'],
        ]);
        $v13['baz']['qux'] = new ExpectedValues(['zot' => &$v13['baz']]);

        yield 'RecursiveTraversalTest.php:'.__LINE__ => [
            'args'    => [],
            'values'  => $v13,
            'visitor' => new DummyRecursiveVisitor(),
            'expect'  => [
                ['func' => 'enter', 'node' => $v13, 'path' => []],
                ['func' => 'visit', 'node' => $v13['foo'], 'path' => ['foo']],
                ['func' => 'visit', 'node' => $v13['bar'], 'path' => ['bar']],
                ['func' => 'enter', 'node' => $v13['baz'], 'path' => ['baz']],
                ['func' => 'visit', 'node' => $v13['baz']['cor'], 'path' => ['baz', 'cor']],
                ['func' => 'enter', 'node' => $v13['baz']['qux'], 'path' => ['baz', 'qux']],
                ['func' => 'cycle', 'node' => $v13['baz']['qux']['zot'], 'path' => ['baz', 'qux', 'zot']],
                ['func' => 'leave', 'node' => $v13['baz']['qux'], 'path' => ['baz', 'qux']],
                ['func' => 'leave', 'node' => $v13['baz'], 'path' => ['baz']],
                ['func' => 'leave', 'node' => $v13, 'path' => []],
            ],
        ];
    }

    /**
     * @dataProvider provWalk
     *
     * @param array $args
     * @param mixed $expect
     */
    public function testWalk(array $args, ValuesInterface $values, DummyRecursiveVisitor $visitor, $expect): void
    {
        $traversal = new RecursiveTraversal(...$args);

        $traversal->walk($values, $visitor);

        $this->assertSame($expect, $visitor->trace());
    }
}
// vim: syntax=php sw=4 ts=4 et:
