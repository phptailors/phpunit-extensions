<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ArrayResult\DummyArrayResult;
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveResultFactory\RecursiveExpectedResultFactoryStackItem
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type CtorArgs   = list{0: ArrayLike, 1: array-key, 2: array|\ArrayAccess}
 * @psalm-type CtorExpect = array{node: mixed, key: mixed, result: mixed}
 * @psalm-type SetExpect  = array{node: mixed, key: mixed, result: mixed}
 */
final class RecursiveExpectedResultFactoryStackItemTest extends TestCase
{
    /**
     * @psalm-return iterable<string,array{ctor: CtorArgs, expect: CtorExpect}>
     */
    public static function provConstruct(): iterable
    {
        //
        // 01
        //

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [['n' => 'N'], 0, []],
            'expect' => [
                'node'   => ['n' => 'N'],
                'key'    => 0,
                'result' => [],
            ],
        ];

        //
        // 02
        //
        $n02 = new DummyArrayResult(false);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [$n02, 'k', []],
            'expect' => [
                'node'   => $n02,
                'key'    => 'k',
                'result' => [],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     *
     * @psalm-param CtorArgs   $ctor
     * @psalm-param CtorExpect $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstruct(array $ctor, array $expect): void
    {
        $item = new RecursiveExpectedResultFactoryStackItem(...$ctor);

        $this->assertSame($expect['node'], $item->node());
        $this->assertSame($expect['key'], $item->key());
        $this->assertSame($expect['result'], $item->result());
    }

    /**
     * @psalm-return iterable<string,array{ctor: CtorArgs, value: mixed, expect: SetExpect}>
     */
    public static function provSet(): iterable
    {
        //
        // 01
        //

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [['n' => 'N'], 0, ['r' => 'R']],
            'value'  => 'V',
            'expect' => [
                'node'   => ['n' => 'N'],
                'key'    => 0,
                'result' => ['r' => 'R', 0 => 'V'],
            ],
        ];

        //
        // 02
        //

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [['n' => 'N'], 'v', ['r' => 'R']],
            'value'  => 'V',
            'expect' => [
                'node'   => ['n' => 'N'],
                'key'    => 'v',
                'result' => ['r' => 'R', 'v' => 'V'],
            ],
        ];

        //
        // 03
        //
        $n03 = new DummyArrayResult(false, ['n' => 'N']);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [$n03, 'v', ['v' => null, 'r' => 'R']],
            'value'  => 'V',
            'expect' => [
                'node'   => $n03,
                'key'    => 'v',
                'result' => ['v' => 'V', 'r' => 'R'],
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
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSet(array $ctor, $value, array $expect): void
    {
        $item = new RecursiveExpectedResultFactoryStackItem(...$ctor);

        $item->set($value);

        $this->assertSame($expect['node'], $item->node());
        $this->assertSame($expect['key'], $item->key());
        $this->assertSame($expect['result'], $item->result());
    }
}
// vim: syntax=php sw=4 ts=4 et:
