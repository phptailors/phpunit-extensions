<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Result;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\TagInterface;
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Result\DummyArrayResult
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type CtorArgs    = list{0:bool,1?:ArrayLike,2?:null|string}
 * @psalm-type ExpectArray = array{actual: mixed, array: mixed}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class DummyArrayResultTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsArrayObject(): void
    {
        $this->assertInstanceOf(\ArrayObject::class, new DummyArrayResult(false));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultInterface(): void
    {
        $this->assertInstanceOf(ResultInterface::class, new DummyArrayResult(false));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotImplementsTagInterface(): void
    {
        $this->assertNotInstanceOf(TagInterface::class, new DummyArrayResult(false));
    }

    /**
     * @psalm-return \Generator<string,array{ctor: CtorArgs, expect: ExpectArray}>
     */
    public static function provDummyArrayResult(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [false],
            'expect' => [
                'actual' => false,
                'array'  => [],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [false, ['foo' => 'FOO']],
            'expect' => [
                'actual' => false,
                'array'  => ['foo' => 'FOO'],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [false, new \ArrayObject(['foo' => 'FOO'])],
            'expect' => [
                'actual' => false,
                'array'  => ['foo' => 'FOO'],
            ],
        ];
    }

    /**
     * @dataProvider provDummyArrayResult
     *
     * @psalm-param CtorArgs    $ctor
     * @psalm-param ExpectArray $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyArrayResult(array $ctor, array $expect): void
    {
        $arrayResult = new DummyArrayResult(...$ctor);

        $this->assertSame($expect['actual'], $arrayResult->actual());
        $this->assertSame($expect['array'], iterator_to_array($arrayResult));
        $this->assertSame($expect['array'], (array) $arrayResult);
    }
}
// vim: syntax=php sw=4 ts=4 et:
