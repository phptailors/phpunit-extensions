<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs    = list{0:bool,1?:array|\Traversable}
 * @psalm-type ExpectArray = array{actual: mixed, array: mixed, tag: mixed}
 */
#[CoversClass(DummyValues::class)]
#[Small]
final class DummyValuesTest extends TestCase
{
    /**
     * @psalm-return iterable<string,array{ctor: CtorArgs, expect: ExpecteArray}
     */
    public static function provDummyValues(): iterable
    {
        $tag = DummyValues::class.':a1a44e79c791a1fe22ac49067eef00b222d10131';

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
    }

    /**
     * @psalm-param CtorArgs    $ctor
     * @psalm-param ExpectArray $expect
     */
    #[DataProvider('provDummyValues')]
    public function testDummyValues(array $ctor, array $expect): void
    {
        $values = new DummyValues(...$ctor);

        $this->assertSame($expect['actual'], $values->actual());
        $this->assertSame($expect['array'], iterator_to_array($values));
        $this->assertSame($expect['array'], (array) $values);
        $this->assertSame($expect['tag'], $values->tag());
    }

    public function testCreateActualValues(): void
    {
        $expect = new DummyValues(false, ['e' => 'E']);

        $actual = $expect->createActualValues(['a' => 'A']);

        $this->assertTrue($actual->actual());
        $this->assertSame(['a' => 'A'], (array) $actual);
        $this->assertSame($expect->tag(), $actual->tag());
    }
}
// vim: syntax=php sw=4 ts=4 et:
