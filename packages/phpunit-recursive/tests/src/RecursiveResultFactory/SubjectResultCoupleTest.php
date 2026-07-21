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

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveResultFactory\SubjectResultCouple
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs    = list{0: mixed, 1: array|\ArrayAccess}
 * @psalm-type ExpectArray = array{subject: mixed, result: mixed}
 */
final class SubjectResultCoupleTest extends TestCase
{
    /**
     * @psalm-return \Generator<non-falsy-string, array{ctor: CtorArgs, expect: ExpectArray}>
     */
    public static function provSubjectResultCouple(): iterable
    {
        //
        // 01
        //

        yield 'SubjectResultCoupleTest.php:'.__LINE__ => [
            'ctor'   => [null, ['A']],
            'expect' => [
                'subject' => null,
                'result'  => ['A'],
            ],
        ];

        //
        // 02
        //

        $a02 = new \ArrayObject(['Y']);

        yield 'SubjectResultCoupleTest.php:'.__LINE__ => [
            'ctor'   => ['X', $a02],
            'expect' => [
                'subject' => 'X',
                'result'  => $a02,
            ],
        ];
    }

    /**
     * @dataProvider provSubjectResultCouple
     *
     * @psalm-param CtorArgs    $ctor
     * @psalm-param ExpectArray $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSubjectResultCouple(array $ctor, array $expect): void
    {
        $couple = new SubjectResultCouple(...$ctor);

        $this->assertSame($expect['subject'], $couple->subject);
        $this->assertSame($expect['result'], $couple->result);
    }
}
// vim: syntax=php sw=4 ts=4 et:
