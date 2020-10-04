<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Tailors\PHPUnit\Constraint;

/**
 * @internal This trait is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
trait ProvHasPregCapturesTrait
{
    // @codeCoverageIgnoreStart

    public static function provHasPregCaptures(): array
    {
        $defaultMessage = 'array does not have expected PCRE capture groups';
        $cases = [
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [],
                'actual' => [],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => false],
                'actual' => [],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => false],
                'actual' => [0 => null],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => false],
                'actual' => [0 => [null, -1]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => false, 'foo' => false, 'bar' => false, 'gez' => false],
                'actual' => [],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [],
                'actual' => [0 => 'FOO'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => 'FOO'],
                'actual' => [0 => 'FOO'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true],
                'actual' => [0 => 'FOO'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'foo' => false],
                'actual' => [0 => 'FOO'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'bar' => false],
                'actual' => [0 => 'FOO'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'gez' => false],
                'actual' => [0 => 'FOO'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'foo' => false, 'bar' => false],
                'actual' => [0 => 'FOO'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'foo' => false, 'gez' => false],
                'actual' => [0 => 'FOO'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'foo' => false, 'bar' => false, 'gez' => false],
                'actual' => [0 => 'FOO'],
            ],

            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [],
                'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true],
                'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'foo' => false],
                'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'bar' => true],
                'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'gez' => false],
                'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'foo' => false, 'bar' => true],
                'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'foo' => false, 'gez' => false],
                'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'foo' => false, 'bar' => true, 'gez' => false],
                'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => 'FOO BAR'],
                'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['bar' => 'BAR'],
                'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => 'FOO BAR', 'bar' => 'BAR'],
                'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => 'FOO BAR', 'bar' => 'BAR', 'gez' => false],
                'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => 'FOO BAR', 'bar' => 'BAR', 'gez' => false],
                'actual' => [0 => 'FOO BAR', 'bar' => 'BAR', 'gez' => null],
            ],

            //
            // PREG_OFFSET_CAPTURE
            //

            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'foo' => false],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'bar' => true],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'gez' => false],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'foo' => false, 'bar' => true],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'foo' => false, 'gez' => false],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'foo' => false, 'bar' => true, 'gez' => false],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => 'FOO BAR'],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['bar' => ['BAR', 4]],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['bar' => true],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => true, 'bar' => true],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => [0 => 'FOO BAR', 'bar' => ['BAR', 4], 'gez' => false],
                'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4], 'gez' => [null, -1]],
            ],

            // other corner cases
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => false],
                'actual' => ['foo' => false],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => false],
                'actual' => ['foo' => true],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => false],
                'actual' => ['foo' => [false]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => false],
                'actual' => ['foo' => [true]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => null],
                'actual' => ['foo' => null],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => [null, -1]],
                'actual' => ['foo' => [null, -1]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => true],
                'actual' => ['foo' => ''],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => false],
                'actual' => ['foo' => []],
            ],
        ];

        foreach ($cases as &$case) {
            if (null === ($case['message'] ?? null)) {
                $case['message'] = $defaultMessage;
            }
        }

        return $cases;
    }

    /**
     * Suitable for both assertHasPregCaptures() and hasPregCaptures().
     */
    public function provNotHasPregCaptures(): array
    {
        $defaultMessage = 'array has expected PCRE capture groups';

        $cases = [
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => true],
                'actual' => [],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => true],
                'actual' => ['foo' => null],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => true],
                'actual' => ['foo' => [null, -1]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => [],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => ['bar' => 'FOO'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => ['foo' => [null, -1]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'FOO'],
                'actual' => ['foo' => ['FOO', -1]],
            ],

            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => false],
                'actual' => ['foo' => 'FOO'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'BAR'],
                'actual' => ['foo' => 'FOO'],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => 'BAR'],
                'actual' => ['foo' => ['FOO', -1]],
            ],

            // other corner cases
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => null],
                'actual' => [],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => null],
                'actual' => ['foo' => [null, -1]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => [null, -1]],
                'actual' => [],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => [null, -1]],
                'actual' => ['foo' => null],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => true],
                'actual' => ['foo' => false],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => true],
                'actual' => ['foo' => true],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => true],
                'actual' => ['foo' => [false]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => true],
                'actual' => ['foo' => [true]],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => false],
                'actual' => ['foo' => ''],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => true],
                'actual' => ['foo' => []],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => [null, -1]],
                'actual' => ['foo' => ''],
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect' => ['foo' => [null, -1]],
                'actual' => ['foo' => []],
            ],
        ];

        foreach ($cases as &$case) {
            if (null === ($case['message'] ?? null)) {
                $case['message'] = $defaultMessage;
            }
        }

        return $cases;
    }

    /**
     * Suitable only for hasPregCaptures().
     */
    public function provNotHasPregCapturesNonArray(): array
    {
        return [
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect'  => ['foo' => false],
                'actual'  => 'stuff',
                'message' => 'string has expected PCRE capture groups',
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect'  => ['foo' => false],
                'actual'  => 123,
                'message' => sprintf('%s has expected PCRE capture groups', gettype(123)),
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect'  => ['foo' => false],
                'actual'  => null,
                'message' => sprintf('%s has expected PCRE capture groups', gettype(null)),
            ],
            'ProvHasPregCapturesTrait.php:'.__LINE__ => [
                'expect'  => ['foo' => false],
                'actual'  => new \stdClass(),
                'message' => sprintf('object stdClass has expected PCRE capture groups'),
            ],
        ];
    }

    // @codeCoverageIgnoreStop
}

// vim: syntax=php sw=4 ts=4 et:
