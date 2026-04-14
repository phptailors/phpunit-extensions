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
 * @psalm-type ArgT = bool|ClosureT
 */
final class DummyRecursiveVisitorTest extends TestCase
{
    /**
     * @return iterable<string,array{args: array, expect: array}>
     *
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
                fn ($node, array $path): bool => false,
                fn ($node, array $path): bool => true,
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
     * @param array $args
     * @param array $expect
     *
     * @psalm-param array<ArgT> $args
     * @psalm-param array{enter: mixed, cycle: mixed} $expect
     */
    public function testDummyRecursiveVisitor(array $args, array $expect): void
    {
        $node = new ExpectedValues();

        // Mostly for code coverage.
        $visitor = new DummyRecursiveVisitor(...$args);
        $this->assertSame($expect['enter'], $visitor->enter($node, []));
        $this->assertNull($visitor->visit(null, [], true));
        $this->assertNull($visitor->leave($node, [], true));
        $this->assertSame($expect['cycle'], $visitor->cycle(null, []));

        $trace = [
            ['func' => 'enter', 'node' => $node, 'path' => []],
            ['func' => 'visit', 'node' => null, 'path' => []],
            ['func' => 'leave', 'node' => $node, 'path' => []],
            ['func' => 'cycle', 'node' => null, 'path' => []],
        ];
        $this->assertSame($trace, $visitor->trace());
    }
}
// vim: syntax=php sw=4 ts=4 et:
