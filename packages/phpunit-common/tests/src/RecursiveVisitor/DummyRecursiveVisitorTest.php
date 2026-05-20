<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveVisitor;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Values\DummyValues;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveVisitor\DummyRecursiveVisitor
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type StackItem = DummyRecursiveVisitorStackItem
 * @psalm-type CtorArgClosure = \Closure(array|\Traversable,list<StackItem>):bool
 * @psalm-type CtorArg  = bool|CtorArgClosure
 * @psalm-type CtorArgs = list{0?: CtorArg, 1?: CtorArg}
 * @psalm-type Expect = array{enter: mixed, cycle: mixed}
 */
final class DummyRecursiveVisitorTest extends TestCase
{
    /**
     * @psalm-return \Generator<string,array{ctor: CtorArgs, expect: Expect}>
     * @psalm-suppress UnusedClosureParam
     */
    public static function provDummyRecursiveVisitor(): iterable
    {
        yield 'DummyRecursiveVisitorTest.php:'.__LINE__ => [
            'ctor'   => [],
            'expect' => [
                'enter' => true,
                'cycle' => false,
            ],
        ];

        yield 'DummyRecursiveVisitorTest.php:'.__LINE__ => [
            'ctor'   => [false, true],
            'expect' => [
                'enter' => false,
                'cycle' => true,
            ],
        ];

        yield 'DummyRecursiveVisitorTest.php:'.__LINE__ => [
            'ctor' => [
                /** @psalm-param mixed $node */
                function ($node, array $stack): bool {
                    return false;
                },

                /** @psalm-param mixed $node */
                function ($node, array $stack): bool {
                    return true;
                },
            ],
            'expect' => [
                'enter' => false,
                'cycle' => true,
            ],
        ];
    }

    /**
     * @dataProvider provDummyRecursiveVisitor
     *
     * @psalm-param CtorArgs                       $ctor
     * @psalm-param Expect $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyRecursiveVisitor(array $ctor, array $expect): void
    {
        $node = new DummyValues(false, ['foo' => 'FOO']);
        $stack = [];

        // Mostly for code coverage.
        $visitor = new DummyRecursiveVisitor(...$ctor);
        $this->assertSame($expect['enter'], $visitor->enter($node, $stack));
        array_push($stack, $visitor->makeStackItem($node, 'foo', $stack));
        $this->assertNull($visitor->visit($node['foo'], $stack, true));
        $this->assertNull($visitor->freeStackItem(array_pop($stack), $stack));
        $this->assertNull($visitor->leave($node, $stack, true));
        $this->assertSame($expect['cycle'], $visitor->cycle([], []));

        $trace = [
            ['func' => 'enter', 'node' => $node, 'path' => []],
            ['func' => 'makeStackItem', 'node' => $node, 'key' => 'foo', 'path' => []],
            ['func' => 'visit', 'node' => $node['foo'], 'path' => ['foo']],
            ['func' => 'freeStackItem', 'node' => $node, 'key' => 'foo', 'path' => []],
            ['func' => 'leave', 'node' => $node, 'path' => []],
            ['func' => 'cycle', 'node' => [], 'path' => []],
        ];
        $this->assertSame($trace, $visitor->trace());
    }
}
// vim: syntax=php sw=4 ts=4 et:
