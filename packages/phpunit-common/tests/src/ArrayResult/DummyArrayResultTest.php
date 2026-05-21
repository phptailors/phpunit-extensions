<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\ArrayResult\DummyArrayResult;
use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\DummyValues
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs    = list{0:bool,1?:array|\Traversable,2?:null|string}
 * @psalm-type ExpectArray = array{actual: mixed, array: mixed, tag: mixed}
 */
final class DummyValuesTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsArrayObject(): void
    {
        $this->assertInstanceOf(\ArrayObject::class, new DummyArrayResult(false));
    }

    /**
     * @psalm-return \Generator<string,array{ctor: CtorArgs, expect: ExpectArray}>
     */
    public static function provDummyValues(): iterable
    {
        $tag = DummyArrayResult::class.':a1a44e79c791a1fe22ac49067eef00b222d10131';

        yield 'DummyValuesTest.php:'.__LINE__ => [
            'ctor'   => [false],
            'expect' => [
                'actual' => false,
                'array'  => [],
                'tag'    => $tag,
            ],
        ];

        yield 'DummyValuesTest.php:'.__LINE__ => [
            'ctor'   => [false, ['foo' => 'FOO']],
            'expect' => [
                'actual' => false,
                'array'  => ['foo' => 'FOO'],
                'tag'    => $tag,
            ],
        ];

        yield 'DummyValuesTest.php:'.__LINE__ => [
            'ctor'   => [false, new \ArrayObject(['foo' => 'FOO'])],
            'expect' => [
                'actual' => false,
                'array'  => ['foo' => 'FOO'],
                'tag'    => $tag,
            ],
        ];

        yield 'DummyValuesTest.php'.__LINE__ => [
            'ctor'   => [false, [], 'FOO'],
            'expect' => [
                'actual' => false,
                'array'  => [],
                'tag'    => 'FOO',
            ],
        ];
    }

    /**
     * @dataProvider provDummyValues
     *
     * @psalm-param CtorArgs    $ctor
     * @psalm-param ExpectArray $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyValues(array $ctor, array $expect): void
    {
        $values = new DummyArrayResult(...$ctor);

        $this->assertSame($expect['actual'], $values->actual());
        $this->assertSame($expect['array'], iterator_to_array($values));
        $this->assertSame($expect['array'], (array) $values);
        $this->assertSame($expect['tag'], $values->tag());
    }
}
// vim: syntax=php sw=4 ts=4 et:
