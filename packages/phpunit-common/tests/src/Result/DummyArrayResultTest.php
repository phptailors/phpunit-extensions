<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Result;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\TagInterface;
use Tailors\PHPUnit\Common\TypesInterface;

/**
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
#[CoversClass(DummyArrayResult::class)]
#[CoversClass(DummyArrayResultTest::class)]
#[Small]
final class DummyArrayResultTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsArrayObject(): void
    {
        self::assertInstanceOf(\ArrayObject::class, new DummyArrayResult(false));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultInterface(): void
    {
        self::assertInstanceOf(ResultInterface::class, new DummyArrayResult(false));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotImplementsTagInterface(): void
    {
        self::assertNotInstanceOf(TagInterface::class, new DummyArrayResult(false));
    }

    /**
     * @psalm-return \Generator<string,array{ctor: CtorArgs, expect: ExpectArray}>
     *
     * @codeCoverageIgnore
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
     * @psalm-param CtorArgs    $ctor
     * @psalm-param ExpectArray $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provDummyArrayResult')]
    public function testDummyArrayResult(array $ctor, array $expect): void
    {
        $arrayResult = new DummyArrayResult(...$ctor);

        self::assertSame($expect['actual'], $arrayResult->actual());
        self::assertSame($expect['array'], iterator_to_array($arrayResult));
        self::assertSame($expect['array'], (array) $arrayResult);
    }
}
// vim: syntax=php sw=4 ts=4 et:
