<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\ValueSelector\DummyValueSelector;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArrayResult\DummyExpectedArrayResult
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type CtorArgs    = list{0:ValueSelectorInterface,1?:ArrayLike}
 * @psalm-type ExpectArray = array{selector: mixed, array: mixed, actual: mixed, tag: mixed}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class DummyExpectedArrayResultTest extends TestCase
{
    /**
     * @psalm-return iterable<string, array{ctor: CtorArgs, expect: ExpectArray}>
     */
    public static function provDummyExpectedArrayResult(): iterable
    {
        $tag = DummyArrayResult::class.':a1a44e79c791a1fe22ac49067eef00b222d10131';
        $selector = new DummyValueSelector();

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [$selector],
            'expect' => [
                'selector' => $selector,
                'actual'   => false,
                'array'    => [],
                'tag'      => $tag,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [$selector, ['foo' => 'FOO']],
            'expect' => [
                'selector' => $selector,
                'actual'   => false,
                'array'    => ['foo' => 'FOO'],
                'tag'      => $tag,
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
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
     * @dataProvider provDummyExpectedArrayResult
     *
     * @psalm-param CtorArgs    $ctor
     * @psalm-param ExpectArray $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyExpectedArrayResult(array $ctor, array $expect): void
    {
        $values = new DummyExpectedArrayResult(...$ctor);

        $this->assertSame($expect['selector'], $values->getValueSelector());
        $this->assertSame($expect['actual'], $values->actual());
        $this->assertSame($expect['array'], iterator_to_array($values));
        $this->assertSame($expect['array'], (array) $values);
        $this->assertSame($expect['tag'], $values->tag());
    }
}
// vim: syntax=php sw=4 ts=4 et:
