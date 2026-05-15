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
 * @psalm-type CtorArgs = list{0:mixed, 1:array|ValuesInterface}
 */
#[CoversClass(RecursiveSelectorState::class)]
#[Small]
final class RecursiveSelectorStateTest extends TestCase
{
    /**
     * @psalm-return iterable<string,array{
     *      ctor: CtorArgs,
     *      expect: array{subject: mixed, result: mixed}
     * }>
     */
    public static function provRecursiveSelectorState(): iterable
    {
        //
        // 01
        //
        yield 'RecursiveSelectorStateTest.php:'.__LINE__ => [
            'ctor'   => [null, []],
            'expect' => [
                'subject' => null,
                'result'  => [],
            ],
        ];

        //
        // 02
        //
        yield 'RecursiveSelectorStateTest.php:'.__LINE__ => [
            'ctor'   => ['s', ['r' => 'R']],
            'expect' => [
                'subject' => 's',
                'result'  => ['r' => 'R'],
            ],
        ];

        //
        // 03
        //
        $v03 = new DummyValues(true);

        yield 'RecursiveSelectorStateTest.php:'.__LINE__ => [
            'ctor'   => [['s'], $v03],
            'expect' => [
                'subject' => ['s'],
                'result'  => $v03,
            ],
        ];
    }

    /**
     * @psalm-param CtorArgs                             $ctor
     * @psalm-param array{subject: mixed, result: mixed} $expect
     */
    #[DataProvider('provRecursiveSelectorState')]
    public function testRecursiveSelectorState(array $ctor, array $expect): void
    {
        $state = new RecursiveSelectorState(...$ctor);

        $this->assertSame($expect['subject'], $state->subject);
        $this->assertSame($expect['result'], $state->result);
    }
}
// vim: syntax=php sw=4 ts=4 et:
