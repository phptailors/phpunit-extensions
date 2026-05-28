<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

use Tailors\PHPUnit\ArrayResult\DummyUntaggedArrayResult;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\TagInterface;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArrayResult\DummyUntaggedArrayResult
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike   = iterable<array-key, mixed>
 * @psalm-type CtorArgs    = list{0:bool,1?:ArrayLike,2?:null|string}
 * @psalm-type ExpectArray = array{actual: mixed, array: mixed}
 */
final class DummyUntaggedArrayResultTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsArrayObject(): void
    {
        $this->assertInstanceOf(\ArrayObject::class, new DummyUntaggedArrayResult(false));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultInterface(): void
    {
        $this->assertInstanceOf(ResultInterface::class, new DummyUntaggedArrayResult(false));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotImplementsTagInterface(): void
    {
        $this->assertNotInstanceOf(TagInterface::class, new DummyUntaggedArrayResult(false));
    }

    /**
     * @psalm-return \Generator<string,array{ctor: CtorArgs, expect: ExpectArray}>
     */
    public static function provDummyUntaggedArrayResult(): iterable
    {
        $tag = DummyUntaggedArrayResult::class.':a1a44e79c791a1fe22ac49067eef00b222d10131';

        yield 'DummyUntaggedArrayResultTest.php:'.__LINE__ => [
            'ctor'   => [false],
            'expect' => [
                'actual' => false,
                'array'  => [],
            ],
        ];

        yield 'DummyUntaggedArrayResultTest.php:'.__LINE__ => [
            'ctor'   => [false, ['foo' => 'FOO']],
            'expect' => [
                'actual' => false,
                'array'  => ['foo' => 'FOO'],
            ],
        ];

        yield 'DummyUntaggedArrayResultTest.php:'.__LINE__ => [
            'ctor'   => [false, new \ArrayObject(['foo' => 'FOO'])],
            'expect' => [
                'actual' => false,
                'array'  => ['foo' => 'FOO'],
            ],
        ];
    }

    /**
     * @dataProvider provDummyUntaggedArrayResult
     *
     * @psalm-param CtorArgs    $ctor
     * @psalm-param ExpectArray $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyUntaggedArrayResult(array $ctor, array $expect): void
    {
        $arrayResult = new DummyUntaggedArrayResult(...$ctor);

        $this->assertSame($expect['actual'], $arrayResult->actual());
        $this->assertSame($expect['array'], iterator_to_array($arrayResult));
        $this->assertSame($expect['array'], (array) $arrayResult);
    }
}
// vim: syntax=php sw=4 ts=4 et:
