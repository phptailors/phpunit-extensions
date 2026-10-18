<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs    = list{0: mixed, 1: array|\ArrayAccess}
 * @psalm-type ExpectArray = array{subject: mixed, result: mixed}
 */
#[CoversClass(SubjectResultCouple::class)]
#[CoversClass(SubjectResultCoupleTest::class)]
#[Small]
final class SubjectResultCoupleTest extends TestCase
{
    /**
     * @psalm-return \Generator<non-falsy-string, array{ctor: CtorArgs, expect: ExpectArray}>
     *
     * @codeCoverageIgnore
     */
    public static function provSubjectResultCouple(): iterable
    {
        //
        // 01
        //

        yield basename(__FILE__).':'.__LINE__ => [
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

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => ['X', $a02],
            'expect' => [
                'subject' => 'X',
                'result'  => $a02,
            ],
        ];
    }

    /**
     * @psalm-param CtorArgs    $ctor
     * @psalm-param ExpectArray $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provSubjectResultCouple')]
    public function testSubjectResultCouple(array $ctor, array $expect): void
    {
        $couple = new SubjectResultCouple(...$ctor);

        $this->assertSame($expect['subject'], $couple->subject);
        $this->assertSame($expect['result'], $couple->result);
    }
}
// vim: syntax=php sw=4 ts=4 et:
