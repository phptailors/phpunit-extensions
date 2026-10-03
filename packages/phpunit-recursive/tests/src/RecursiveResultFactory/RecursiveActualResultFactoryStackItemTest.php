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

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveResultFactory\RecursiveActualResultFactoryStackItem
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 *
 * @psalm-type CtorArgs   = list{0: ArrayLike, 1: array-key, 2: SubjectResultCouple}
 * @psalm-type CtorExpect = array{node: mixed, key: mixed, couple: mixed}
 * @psalm-type SetExpect  = array{node: mixed, key: mixed, subject: mixed, result: mixed}
 */
final class RecursiveActualResultFactoryStackItemTest extends TestCase
{
    /**
     * @psalm-return iterable<string, array{ctor: CtorArgs, expect: CtorExpect}>
     */
    public static function provConstruct(): iterable
    {
        //
        // 01
        //
        $c01 = new SubjectResultCouple(null, []);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [['n' => 'N'], '', $c01],
            'expect' => [
                'node'   => ['n' => 'N'],
                'key'    => '',
                'couple' => $c01,
            ],
        ];

        //
        // 02
        //
        $c02 = new SubjectResultCouple(null, []);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [['n' => 'N'], 7, $c02],
            'expect' => [
                'node'   => ['n' => 'N'],
                'key'    => 7,
                'couple' => $c02,
            ],
        ];

        //
        // 03
        //
        $n03 = new DummyArrayResult(false);
        $c03 = new SubjectResultCouple(null, []);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [$n03, 'k', $c03],
            'expect' => [
                'node'   => $n03,
                'key'    => 'k',
                'couple' => $c03,
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
        $item = new RecursiveActualResultFactoryStackItem(...$ctor);

        $this->assertSame($expect['node'], $item->node());
        $this->assertSame($expect['key'], $item->key());
        $this->assertSame($expect['couple'], $item->current());
    }

    /**
     * @psalm-return iterable<string,array{ctor: CtorArgs, value: mixed, expect: SetExpect}>
     */
    public static function provSet(): iterable
    {
        //
        // 01
        //
        $s01 = new SubjectResultCouple(null, ['r' => 'R']);

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [['n' => 'N'], '', $s01],
            'value'  => 'V',
            'expect' => [
                'node'    => ['n' => 'N'],
                'key'     => '',
                'subject' => null,
                'result'  => ['r' => 'R', null => 'V'],
            ],
        ];

        //
        // 02
        //
        $s02 = new SubjectResultCouple('s', ['r' => 'R']);

        yield basename(__FILE__).':'.__LINE__ => [
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
        $s03 = new SubjectResultCouple('s', ['v' => null, 'r' => 'R']);
        $n03 = new DummyArrayResult(false, ['n' => 'N']);

        yield basename(__FILE__).':'.__LINE__ => [
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
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSet(array $ctor, $value, array $expect): void
    {
        $item = new RecursiveActualResultFactoryStackItem(...$ctor);

        $item->set($value);

        $this->assertSame($expect['node'], $item->node());
        $this->assertSame($expect['key'], $item->key());
        $this->assertSame($expect['subject'], $item->current()->subject);
        $this->assertSame($expect['result'], $item->current()->result);
    }
}
// vim: syntax=php sw=4 ts=4 et:
