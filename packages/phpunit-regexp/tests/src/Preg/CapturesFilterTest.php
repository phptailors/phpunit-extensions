<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Preg;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(CapturesFilter::class)]
final class CapturesFilterTest extends TestCase
{
    public static function provConstruct(): array
    {
        return [
            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [],
                'expect' => [],
            ],
            'CapturesFilterTest.php:'.__LINE__ => [
                'args'   => [123],
                'expect' => [],
            ],
        ];
    }

    #[DataProvider('provConstruct')]
    public function testConstruct(array $args, array $expect): void
    {
        $filter = new CapturesFilter(...$args);
        self::assertInstanceOf(CapturesFilterInterface::class, $filter);
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
                'args'   => [0xF0F0F0 | PREG_UNMATCHED_AS_NULL],
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
     * @param mixed $value
     */
    #[DataProvider('provIsCapture')]
    public function testIsCapture(array $args, $value, bool $expect): void
    {
        $filter = new CapturesFilter(...$args);
        $this->assertSame($expect, $filter->accepts($value));
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

    #[DataProvider('provFilter')]
    public function testFilter(array $args, array $array, array $expect): void
    {
        $filter = new CapturesFilter(...$args);
        $this->assertSame($expect, $filter->filter($array));
    }
}

// vim: syntax=php sw=4 ts=4 et:
