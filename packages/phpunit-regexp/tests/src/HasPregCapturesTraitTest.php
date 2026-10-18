<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ProvHasPregCapturesTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\HasPregCapturesTrait
 * @covers \Tailors\PHPUnit\HasPregCapturesTraitTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class HasPregCapturesTraitTest extends TestCase
{
    use HasPregCapturesTrait;
    use ProvHasPregCapturesTrait;

    /**
     * @psalm-return iterable<string, array{
     *      expect: array,
     *      actual: array
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provHasPregCapturesSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provHasPregCaptures();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'expect' => $case['expect'],
                'actual' => $case['actual'],
            ];
        }
    }

    /**
     * @psalm-return iterable<string, array{
     *      expect: array,
     *      actual: array
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provNotHasPregCapturesSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provNotHasPregCaptures();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'expect' => $case['expect'],
                'actual' => $case['actual'],
            ];
        }
    }

    /**
     * @dataProvider provHasPregCapturesSucceeds
     *
     * @psalm-param array $expect
     * @psalm-param array $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testHasPregCapturesSucceeds(array $expect, array $actual): void
    {
        self::assertThat($actual, self::hasPregCaptures($expect));
    }

    /**
     * @dataProvider provHasPregCapturesSucceeds
     *
     * @psalm-param array $expect
     * @psalm-param array $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertHasPregCapturesSucceeds(array $expect, array $actual): void
    {
        self::assertHasPregCaptures($expect, $actual);
    }

    /**
     * @dataProvider provNotHasPregCapturesSucceeds
     *
     * @psalm-param array $expect
     * @psalm-param array $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLogicalNotHasPregCaptureSucceeds(array $expect, array $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::hasPregCaptures($expect)));
    }

    /**
     * @dataProvider provNotHasPregCapturesSucceeds
     *
     * @psalm-param array $expect
     * @psalm-param array $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotHasPregCaptureSucceeds(array $expect, array $actual): void
    {
        self::assertNotHasPregCaptures($expect, $actual);
    }

    /**
     * @dataProvider provNotHasPregCaptures
     *
     * @psalm-param array $expect
     * @psalm-param array $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertHasPregCapturesFails(array $expect, array $actual, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage(sprintf('Failed asserting that %s.', $message));

        self::assertHasPregCaptures($expect, $actual);
    }

    /**
     * @dataProvider provHasPregCaptures
     *
     * @psalm-param array $expect
     * @psalm-param array $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotHasPregCaptureFails(array $expect, array $actual, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage(sprintf('Failed asserting that %s.', $message));
        self::assertNotHasPregCaptures($expect, $actual);
    }

    /**
     * @dataProvider provNotHasPregCaptures
     * @dataProvider provNotHasPregCapturesNonArray
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testHasPregCapturesFails(array $expect, mixed $actual, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage(sprintf('Failed asserting that %s.', $message));

        self::assertThat($actual, self::hasPregCaptures($expect));
    }
}

// vim: syntax=php sw=4 ts=4 et:
