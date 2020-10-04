<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Tailors\PHPUnit\Preg;

use PHPUnit\Framework\TestCase;

/**
 * @small
 * @covers \Tailors\PHPUnit\Preg\CapturesFilter
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class CapturesFilterTest extends TestCase
{
    public static function provConstruct(): array
    {
        return [
            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [],
                'expect' => [
                    'flags' => 0,
                ],
            ],
            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [123],
                'expect' => [
                    'flags' => 123,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $filter = new CapturesFilter(...$args);
        self::assertInstanceOf(CapturesFilterInterface::class, $filter);
        self::assertSame($expect['flags'], $filter->getFlags());
    }

    public static function provIsCapture(): array
    {
        return [
            // typical scalar values
            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [],
                'value'  => null,
                'expect' => false,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_UNMATCHED_AS_NULL],
                'value'  => null,
                'expect' => true,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [0xf0f0f0 | PREG_UNMATCHED_AS_NULL],
                'value'  => null,
                'expect' => true,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [],
                'value'  => '',
                'expect' => true,
            ],

            // typical array values
            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [],
                'value'  => ['', 0],
                'expect' => false,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_OFFSET_CAPTURE],
                'value'  => ['', 0],
                'expect' => true,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_OFFSET_CAPTURE],
                'value'  => [null, 0],
                'expect' => false,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL],
                'value'  => [null, 0],
                'expect' => true,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_UNMATCHED_AS_NULL],
                'value'  => [null, 0],
                'expect' => false,
            ],

            // abnormal scalars
            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [],
                'value'  => 123,
                'expect' => false,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [],
                'value'  => 123.456,
                'expect' => false,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [],
                'value'  => true,
                'expect' => false,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [],
                'value'  => false,
                'expect' => false,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [],
                'value'  => new \stdClass(),
                'expect' => false,
            ],

            // abnomral arrays
            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_OFFSET_CAPTURE],
                'value'  => [],
                'expect' => false,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_OFFSET_CAPTURE],
                'value'  => [''],
                'expect' => false,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL],
                'value'  => [null],
                'expect' => false,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_OFFSET_CAPTURE],
                'value'  => ['', ''],
                'expect' => false,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_OFFSET_CAPTURE],
                'value'  => ['', true],
                'expect' => false,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_OFFSET_CAPTURE],
                'value'  => ['', false],
                'expect' => false,
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_OFFSET_CAPTURE],
                'value'  => ['', 0, null],
                'expect' => false,
            ],
        ];
    }

    /**
     * @dataProvider provIsCapture
     *
     * @param mixed $value
     */
    public function testIsCapture(array $args, $value, bool $expect): void
    {
        $filter = new CapturesFilter(...$args);
        $this->assertSame($expect, $filter->acceptable($value));
    }

    public static function provFilter(): array
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

        return [
            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [],
                'array'  => $array,
                'expect' => [
                    '""'    => '',
                    '"foo"' => 'foo',
                ],
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_UNMATCHED_AS_NULL],
                'array'  => $array,
                'expect' => [
                    '""'    => '',
                    'null'  => null,
                    '"foo"' => 'foo',
                ],
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_OFFSET_CAPTURE],
                'array'  => $array,
                'expect' => [
                    '""'      => '',
                    '"foo"'   => 'foo',
                    '["",-1]' => ['', -1],
                ],
            ],

            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL],
                'array'  => $array,
                'expect' => [
                    '""'       => '',
                    'null'     => null,
                    '"foo"'    => 'foo',
                    '["",-1]'  => ['', -1],
                    '[null,0]' => [null, -1],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provFilter
     */
    public function testFilter(array $args, array $array, array $expect): void
    {
        $filter = new CapturesFilter(...$args);
        $this->assertSame($expect, $filter->filter($array));
    }
}

// vim: syntax=php sw=4 ts=4 et:
