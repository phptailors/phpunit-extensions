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

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorUtils
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type StackItem = RecursiveVisitorStackItemInterface
 */
final class RecursiveVisitorUtilsTest extends TestCase
{
    /**
     * @psalm-return \Generator<non-falsy-string, array{stack: list<StackItem>, expect: mixed}>
     */
    public static function provPathAsList(): iterable
    {
        yield 'RecursiveVisitorUtilsTest.php:'.__LINE__ => [
            'stack'  => [],
            'expect' => [],
        ];

        yield 'RecursiveVisitorUtilsTest.php:'.__LINE__ => [
            'stack' => [
                new DummyRecursiveVisitorStackItem([], 'foo'),
            ],
            'expect' => ['foo'],
        ];

        yield 'RecursiveVisitorUtilsTest.php:'.__LINE__ => [
            'stack' => [
                new DummyRecursiveVisitorStackItem([], 'foo'),
                new DummyRecursiveVisitorStackItem([], 'bar'),
                new DummyRecursiveVisitorStackItem([], false),
                new DummyRecursiveVisitorStackItem([], null),
            ],
            'expect' => ['foo', 'bar', false, null],
        ];
    }

    /**
     * @dataProvider provPathAsList
     *
     * @param mixed $expect
     *
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testPathAsList(array $stack, $expect): void
    {
        $this->assertSame($expect, RecursiveVisitorUtils::pathAsList($stack));
    }

    /**
     * @psalm-return \Generator<non-falsy-string, array{stack: list<StackItem>, expect: mixed}>
     */
    public static function provPathAsString(): iterable
    {
        yield 'RecursiveVisitorUtilsTest.php:'.__LINE__ => [
            'stack'  => [],
            'expect' => '',
        ];

        yield 'RecursiveVisitorUtilsTest.php:'.__LINE__ => [
            'stack' => [
                new DummyRecursiveVisitorStackItem([], 'foo'),
            ],
            'expect' => "['foo']",
        ];

        yield 'RecursiveVisitorUtilsTest.php:'.__LINE__ => [
            'stack' => [
                new DummyRecursiveVisitorStackItem([], 'foo'),
                new DummyRecursiveVisitorStackItem([], 'bar'),
                new DummyRecursiveVisitorStackItem([], false),
                new DummyRecursiveVisitorStackItem([], null),
            ],
            'expect' => "['foo']['bar'][false][NULL]",
        ];
    }

    /**
     * @dataProvider provPathAsString
     *
     * @param mixed $expect
     *
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testPathAsString(array $stack, $expect): void
    {
        $this->assertSame($expect, RecursiveVisitorUtils::pathAsString($stack));
    }
}
// vim: syntax=php sw=4 ts=4 et:
