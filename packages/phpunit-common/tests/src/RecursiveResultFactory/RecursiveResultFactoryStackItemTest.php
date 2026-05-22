<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use Tailors\PHPUnit\ArrayResult\DummyArrayResult;
use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveConstraint\RecursiveResultFactoryStackItem
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs   = list{0:array|ValuesInterface, 1:array-key, 2: RecursiveResultFactoryState}
 * @psalm-type CtorExpect = array{node: mixed, key: mixed, state: mixed}
 * @psalm-type SetExpect  = array{node: mixed, key: mixed, subject: mixed, result: mixed}
 */
final class RecursiveResultFactoryStackItemTest extends TestCase
{
    /**
     * @psalm-return iterable<string,array{ctor: CtorArgs, expect: CtorExpect}>
     */
    public static function provConstruct(): iterable
    {
        //
        // 01
        //
        $s01 = new RecursiveResultFactoryState(null, []);

        yield 'RecursiveResultFactoryStackItemTest.php:'.__LINE__ => [
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
        $n02 = new DummyArrayResult(false);
        $s02 = new RecursiveResultFactoryState(null, []);

        yield 'RecursiveResultFactoryStackItemTest.php:'.__LINE__ => [
            'ctor'   => [$n02, 'k', $s02],
            'expect' => [
                'node'  => $n02,
                'key'   => 'k',
                'state' => $s02,
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     *
     * @psalm-param CtorArgs   $ctor
     * @psalm-param CtorExpect $expect
     */
    public function testConstruct(array $ctor, array $expect): void
    {
        $item = new RecursiveResultFactoryStackItem(...$ctor);

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
        $s01 = new RecursiveResultFactoryState(null, ['r' => 'R']);

        yield 'RecursiveResultFactoryStackItemTest.php:'.__LINE__ => [
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
        $s02 = new RecursiveResultFactoryState('s', ['r' => 'R']);

        yield 'RecursiveResultFactoryStackItemTest.php:'.__LINE__ => [
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
        $s03 = new RecursiveResultFactoryState('s', ['v' => null, 'r' => 'R']);
        $n03 = new DummyArrayResult(false, ['n' => 'N']);

        yield 'RecursiveResultFactoryStackItemTest.php:'.__LINE__ => [
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
     * @dataProvider provSet
     *
     * @param mixed $value
     *
     * @psalm-param CtorArgs  $ctor
     * @psalm-param SetExpect $expect
     */
    public function testSet(array $ctor, $value, array $expect): void
    {
        $item = new RecursiveResultFactoryStackItem(...$ctor);

        $item->set($value);

        $this->assertSame($expect['node'], $item->node());
        $this->assertSame($expect['key'], $item->key());
        $this->assertSame($expect['subject'], $item->state()->subject);
        $this->assertSame($expect['result'], $item->state()->result);
    }
}
// vim: syntax=php sw=4 ts=4 et:
