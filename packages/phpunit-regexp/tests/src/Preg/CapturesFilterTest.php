<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Preg;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Preg\CapturesFilter
 * @covers \Tailors\PHPUnit\Preg\CapturesFilterTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs = list{0?: int}
 */
final class CapturesFilterTest extends TestCase
{
    /**
     * @psalm-return iterable<string, array{ctorArgs: CtorArgs}>
     */
    public static function provConstruct(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [123],
        ];
    }

    /**
     * @dataProvider provConstruct
     *
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstruct(array $ctorArgs): void
    {
        $filter = new CapturesFilter(...$ctorArgs);
        self::assertInstanceOf(CapturesFilterInterface::class, $filter);
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      value: mixed,
     *      expect: mixed
     * }>
     */
    public static function provIsCapture(): iterable
    {
        // typical scalar values
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'value'    => null,
            'expect'   => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_UNMATCHED_AS_NULL],
            'value'    => null,
            'expect'   => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [0xF0F0F0 | PREG_UNMATCHED_AS_NULL],
            'value'    => null,
            'expect'   => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'value'    => '',
            'expect'   => true,
        ];

        // typical array values
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'value'    => ['', 0],
            'expect'   => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_OFFSET_CAPTURE],
            'value'    => ['', 0],
            'expect'   => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_OFFSET_CAPTURE],
            'value'    => [null, 0],
            'expect'   => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL],
            'value'    => [null, 0],
            'expect'   => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_UNMATCHED_AS_NULL],
            'value'    => [null, 0],
            'expect'   => false,
        ];

        // abnormal scalars
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'value'    => 123,
            'expect'   => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'value'    => 123.456,
            'expect'   => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'value'    => true,
            'expect'   => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'value'    => false,
            'expect'   => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'value'    => new \stdClass(),
            'expect'   => false,
        ];

        // abnomral arrays
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_OFFSET_CAPTURE],
            'value'    => [],
            'expect'   => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_OFFSET_CAPTURE],
            'value'    => [''],
            'expect'   => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL],
            'value'    => [null],
            'expect'   => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_OFFSET_CAPTURE],
            'value'    => ['', ''],
            'expect'   => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_OFFSET_CAPTURE],
            'value'    => ['', true],
            'expect'   => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_OFFSET_CAPTURE],
            'value'    => ['', false],
            'expect'   => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_OFFSET_CAPTURE],
            'value'    => ['', 0, null],
            'expect'   => false,
        ];
    }

    /**
     * @dataProvider provIsCapture
     *
     * @param mixed $value
     * @param mixed $expect
     *
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testIsCapture(array $ctorArgs, $value, $expect): void
    {
        $filter = new CapturesFilter(...$ctorArgs);
        $this->assertSame($expect, $filter->accepts($value));
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      array: array,
     *      expect: mixed
     * }>
     */
    public static function provFilter(): iterable
    {
        $array = [
            '""'        => '',
            'null'      => null,
            '"foo"'     => 'foo',
            '["",-1]'   => ['', -1],
            '[""]'      => [''],
            '[null,0]'  => [null, -1],
            '["",0,""]' => ['', 0, ''],
            'object'    => new \stdClass(),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'array'    => $array,
            'expect'   => [
                '""'    => '',
                '"foo"' => 'foo',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_UNMATCHED_AS_NULL],
            'array'    => $array,
            'expect'   => [
                '""'    => '',
                'null'  => null,
                '"foo"' => 'foo',
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_OFFSET_CAPTURE],
            'array'    => $array,
            'expect'   => [
                '""'      => '',
                '"foo"'   => 'foo',
                '["",-1]' => ['', -1],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL],
            'array'    => $array,
            'expect'   => [
                '""'       => '',
                'null'     => null,
                '"foo"'    => 'foo',
                '["",-1]'  => ['', -1],
                '[null,0]' => [null, -1],
            ],
        ];
    }

    /**
     * @dataProvider provFilter
     *
     * @param mixed $expect
     *
     * @psalm-param CtorArgs $ctorArgs
     * @psalm-param array    $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFilter(array $ctorArgs, array $array, $expect): void
    {
        $filter = new CapturesFilter(...$ctorArgs);
        $this->assertSame($expect, $filter->filter($array));
    }
}

// vim: syntax=php sw=4 ts=4 et:
