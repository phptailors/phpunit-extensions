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
use Tailors\PHPUnit\Selector\DummyValueSelector;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs     = list{0:ValueSelectorInterface,1?:array|\Traversable}
 * @psalm-type ExpectArrary =  array{selector: mixed, array: mixed, actual: mixed, tag: mixed}
 */
#[CoversClass(DummyExpectedValues::class)]
#[Small]
final class DummyExpectedValuesTest extends TestCase
{
    /**
     * @psalm-return iterable<string,array{ctor: CtorArgs expect: ExpectArray}
     */
    public static function provDummyExpectedValues(): iterable
    {
        $tag = DummyValues::class.':a1a44e79c791a1fe22ac49067eef00b222d10131';
        $selector = new DummyValueSelector();

        yield 'DummyExpectedValuesTest.php:'.__LINE__ => [
            'ctor'   => [$selector],
            'expect' => [
                'selector' => $selector,
                'actual'   => false,
                'array'    => [],
                'tag'      => $tag,
            ],
        ];

        yield 'DummyExpectedValuesTest.php:'.__LINE__ => [
            'ctor'   => [$selector, ['foo' => 'FOO']],
            'expect' => [
                'selector' => $selector,
                'actual'   => false,
                'array'    => ['foo' => 'FOO'],
                'tag'      => $tag,
            ],
        ];

        yield 'DummyExpectedValuesTest.php:'.__LINE__ => [
            'ctor'   => [$selector, new \ArrayObject(['foo' => 'FOO'])],
            'expect' => [
                'selector' => $selector,
                'actual'   => false,
                'array'    => ['foo' => 'FOO'],
                'tag'      => $tag,
            ],
        ];
    }

    /**
     * @psalm-param CtorArgs    $ctor
     * @psalm-param ExpectArray $expect
     */
    #[DataProvider('provDummyExpectedValues')]
    public function testDummyExpectedValues(array $ctor, array $expect): void
    {
        $values = new DummyExpectedValues(...$ctor);

        $this->assertSame($expect['selector'], $values->getValueSelector());
        $this->assertSame($expect['actual'], $values->actual());
        $this->assertSame($expect['array'], iterator_to_array($values));
        $this->assertSame($expect['array'], (array) $values);
        $this->assertSame($expect['tag'], $values->tag());
    }

    public function testCreateActualValues(): void
    {
        $selector = new DummyValueSelector();
        $expect = new DummyExpectedValues($selector, ['e' => 'E']);

        $actual = $expect->createActualValues(['a' => 'A']);

        $this->assertSame($selector, $expect->getValueSelector());
        $this->assertTrue($actual->actual());
        $this->assertSame(['a' => 'A'], (array) $actual);
        $this->assertSame($expect->tag(), $actual->tag());
    }
}
// vim: syntax=php sw=4 ts=4 et:
