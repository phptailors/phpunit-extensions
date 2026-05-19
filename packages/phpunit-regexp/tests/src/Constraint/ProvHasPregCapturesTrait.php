<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
trait ProvHasPregCapturesTrait
{
    public static function provHasPregCaptures(): iterable
    {
        $defaultMessage = 'array does not have expected PCRE capture groups';
        foreach (self::hasPregCapturesTestCases() as $case) {
            if (null === ($case['message'] ?? null)) {
                $case['message'] = $defaultMessage;
            }

            yield $case;
        }
    }

    /**
     * Suitable for both assertHasPregCaptures() and hasPregCaptures().
     */
    public static function notHasPregCapturesTestCases(): iterable
    {
        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => true],
            'actual' => [],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => true],
            'actual' => ['foo' => null],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => true],
            'actual' => ['foo' => [null, -1]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => [],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => ['bar' => 'FOO'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => ['foo' => [null, -1]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'FOO'],
            'actual' => ['foo' => ['FOO', -1]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => false],
            'actual' => ['foo' => 'FOO'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'BAR'],
            'actual' => ['foo' => 'FOO'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => 'BAR'],
            'actual' => ['foo' => ['FOO', -1]],
        ];

        // other corner cases
        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => null],
            'actual' => [],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => null],
            'actual' => ['foo' => [null, -1]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => [null, -1]],
            'actual' => [],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => [null, -1]],
            'actual' => ['foo' => null],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => true],
            'actual' => ['foo' => false],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => true],
            'actual' => ['foo' => true],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => true],
            'actual' => ['foo' => [false]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => true],
            'actual' => ['foo' => [true]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => false],
            'actual' => ['foo' => ''],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => true],
            'actual' => ['foo' => []],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => [null, -1]],
            'actual' => ['foo' => ''],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => [null, -1]],
            'actual' => ['foo' => []],
        ];
    }

    /**
     * Suitable for both assertHasPregCaptures() and hasPregCaptures().
     */
    public static function provNotHasPregCaptures(): iterable
    {
        $defaultMessage = 'array has expected PCRE capture groups';

        foreach (self::notHasPregCapturesTestCases() as $case) {
            if (null === ($case['message'] ?? null)) {
                $case['message'] = $defaultMessage;
            }

            yield $case;
        }
    }

    /**
     * Suitable only for hasPregCaptures().
     */
    public static function provNotHasPregCapturesNonArray(): iterable
    {
        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect'  => ['foo' => false],
            'actual'  => 'stuff',
            'message' => 'string has expected PCRE capture groups',
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect'  => ['foo' => false],
            'actual'  => 123,
            'message' => sprintf('%s has expected PCRE capture groups', gettype(123)),
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect'  => ['foo' => false],
            'actual'  => null,
            'message' => sprintf('%s has expected PCRE capture groups', gettype(null)),
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect'  => ['foo' => false],
            'actual'  => new \stdClass(),
            'message' => sprintf('object stdClass has expected PCRE capture groups'),
        ];
    }
    // @codeCoverageIgnoreStart

    protected static function hasPregCapturesTestCases(): iterable
    {
        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [],
            'actual' => [],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => false],
            'actual' => [],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => false],
            'actual' => [0 => null],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => false],
            'actual' => [0 => [null, -1]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => false, 'foo' => false, 'bar' => false, 'gez' => false],
            'actual' => [],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [],
            'actual' => [0 => 'FOO'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => 'FOO'],
            'actual' => [0 => 'FOO'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true],
            'actual' => [0 => 'FOO'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'foo' => false],
            'actual' => [0 => 'FOO'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'bar' => false],
            'actual' => [0 => 'FOO'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'gez' => false],
            'actual' => [0 => 'FOO'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'foo' => false, 'bar' => false],
            'actual' => [0 => 'FOO'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'foo' => false, 'gez' => false],
            'actual' => [0 => 'FOO'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'foo' => false, 'bar' => false, 'gez' => false],
            'actual' => [0 => 'FOO'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [],
            'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true],
            'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'foo' => false],
            'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'bar' => true],
            'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'gez' => false],
            'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'foo' => false, 'bar' => true],
            'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'foo' => false, 'gez' => false],
            'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'foo' => false, 'bar' => true, 'gez' => false],
            'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => 'FOO BAR'],
            'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['bar' => 'BAR'],
            'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => 'FOO BAR', 'bar' => 'BAR'],
            'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => 'FOO BAR', 'bar' => 'BAR', 'gez' => false],
            'actual' => [0 => 'FOO BAR', 'bar' => 'BAR'],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => 'FOO BAR', 'bar' => 'BAR', 'gez' => false],
            'actual' => [0 => 'FOO BAR', 'bar' => 'BAR', 'gez' => null],
        ];

        //
        // PREG_OFFSET_CAPTURE
        //

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'foo' => false],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'bar' => true],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'gez' => false],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'foo' => false, 'bar' => true],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'foo' => false, 'gez' => false],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'foo' => false, 'bar' => true, 'gez' => false],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => 'FOO BAR'],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['bar' => ['BAR', 4]],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['bar' => true],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => true, 'bar' => true],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => [0 => 'FOO BAR', 'bar' => ['BAR', 4], 'gez' => false],
            'actual' => [0 => 'FOO BAR', 'bar' => ['BAR', 4], 'gez' => [null, -1]],
        ];

        // other corner cases
        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => false],
            'actual' => ['foo' => false],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => false],
            'actual' => ['foo' => true],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => false],
            'actual' => ['foo' => [false]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => false],
            'actual' => ['foo' => [true]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => null],
            'actual' => ['foo' => null],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => [null, -1]],
            'actual' => ['foo' => [null, -1]],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => true],
            'actual' => ['foo' => ''],
        ];

        yield 'ProvHasPregCapturesTrait.php:'.__LINE__ => [
            'expect' => ['foo' => false],
            'actual' => ['foo' => []],
        ];
    }

    // @codeCoverageIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
