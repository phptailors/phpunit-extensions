<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Recursive;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Values\DummyValues;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs   = list{0:array|ValuesInterface, 1:array-key, 2: RecursiveSelectorState}
 * @psalm-type CtorExpect = array{node: mixed, key: mixed, state: mixed}
 * @psalm-type SetExpect  = array{node: mixed, key: mixed, subject: mixed, result: mixed}
 */
#[CoversClass(RecursiveSelectorStackItem::class)]
#[Small]
final class RecursiveSelectorStackItemTest extends TestCase
{
    /**
     * @psalm-return iterable<string,array{ctor: CtorArgs, expect: CtorExpect}>
     */
    public static function provConstruct(): iterable
    {
        //
        // 01
        //
        $s01 = new RecursiveSelectorState(null, []);

        yield 'RecursiveSelectorStackItemTest.php:'.__LINE__ => [
            'ctor'   => [['n' => 'N'], null, $s01],
            'expect' => [
                'node'  => ['n' => 'N'],
                'key'   => null,
                'state' => $s01,
            ],
        ];

        //
        // 02
        //
        $n02 = new DummyValues(false);
        $s02 = new RecursiveSelectorState(null, []);

        yield 'RecursiveSelectorStackItemTest.php:'.__LINE__ => [
            'ctor'   => [$n02, 'k', $s02],
            'expect' => [
                'node'  => $n02,
                'key'   => 'k',
                'state' => $s02,
            ],
        ];
    }

    /**
     * @psalm-param CtorArgs   $ctor
     * @psalm-param CtorExpect $expect
     */
    #[DataProvider('provConstruct')]
    public function testConstruct(array $ctor, array $expect): void
    {
        $item = new RecursiveSelectorStackItem(...$ctor);

        $this->assertSame($expect['node'], $item->node());
        $this->assertSame($expect['key'], $item->key());
        $this->assertSame($expect['state'], $item->state());
    }

    /**
     * @psalm-return iterable<string,array{ctor: CtorArgs, value: mixed, expect: SetExpect}>
     */
    public static function provSet(): iterable
    {
        //
        // 01
        //
        $s01 = new RecursiveSelectorState(null, ['r' => 'R']);

        yield 'RecursiveSelectorStackItemTest.php:'.__LINE__ => [
            'ctor'   => [['n' => 'N'], null, $s01],
            'value'  => 'V',
            'expect' => [
                'node'    => ['n' => 'N'],
                'key'     => null,
                'subject' => null,
                'result'  => ['r' => 'R', null => 'V'],
            ],
        ];

        //
        // 02
        //
        $s02 = new RecursiveSelectorState('s', ['r' => 'R']);

        yield 'RecursiveSelectorStackItemTest.php:'.__LINE__ => [
            'ctor'   => [['n' => 'N'], 'v', $s02],
            'value'  => 'V',
            'expect' => [
                'node'    => ['n' => 'N'],
                'key'     => 'v',
                'subject' => 's',
                'result'  => ['r' => 'R', 'v' => 'V'],
            ],
        ];

        //
        // 03
        //
        $s03 = new RecursiveSelectorState('s', ['v' => null, 'r' => 'R']);
        $n03 = new DummyValues(false, ['n' => 'N']);

        yield 'RecursiveSelectorStackItemTest.php:'.__LINE__ => [
            'ctor'   => [$n03, 'v', $s03],
            'value'  => 'V',
            'expect' => [
                'node'    => $n03,
                'key'     => 'v',
                'subject' => 's',
                'result'  => ['v' => 'V', 'r' => 'R'],
            ],
        ];
    }

    /**
     * @psalm-param CtorArgs  $ctor
     * @psalm-param SetExpect $expect
     */
    #[DataProvider('provSet')]
    public function testSet(array $ctor, mixed $value, array $expect): void
    {
        $item = new RecursiveSelectorStackItem(...$ctor);

        $item->set($value);

        $this->assertSame($expect['node'], $item->node());
        $this->assertSame($expect['key'], $item->key());
        $this->assertSame($expect['subject'], $item->state()->subject);
        $this->assertSame($expect['result'], $item->state()->result);
    }
}
// vim: syntax=php sw=4 ts=4 et:
