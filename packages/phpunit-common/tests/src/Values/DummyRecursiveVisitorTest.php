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
 * @covers \Tailors\PHPUnit\Values\DummyRecursiveVisitor
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ClosureT = \Closure(array|ValuesInterface,list<array-key>):bool
 * @psalm-type ArgT     = bool|ClosureT
 */
final class DummyRecursiveVisitorTest extends TestCase
{
    /**
     * @psalm-return iterable<string,array{args: array<ArgT>, expect: array{enter: mixed, cycle: mixed}}>
     */
    public static function provDummyRecursiveVisitor(): iterable
    {
        yield 'DummyRecursiveVisitorTest.php:'.__LINE__ => [
            'args'   => [],
            'expect' => [
                'enter' => true,
                'cycle' => false,
            ],
        ];

        yield 'DummyRecursiveVisitorTest.php:'.__LINE__ => [
            'args'   => [false, true],
            'expect' => [
                'enter' => false,
                'cycle' => true,
            ],
        ];

        yield 'DummyRecursiveVisitorTest.php:'.__LINE__ => [
            'args' => [
                function ($node, array $stack): bool {
                    return false;
                },

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
     * @psalm-param array<ArgT>                       $args
     * @psalm-param array{enter: mixed, cycle: mixed} $expect
     */
    public function testDummyRecursiveVisitor(array $args, array $expect): void
    {
        $node = new ExpectedValues(['foo' => 'FOO']);
        $stack = [];

        // Mostly for code coverage.
        $visitor = new DummyRecursiveVisitor(...$args);
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
