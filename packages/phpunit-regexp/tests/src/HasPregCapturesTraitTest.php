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
 * @covers \Tailors\PHPUnit\Constraint\ProvHasPregCapturesTrait
 * @covers \Tailors\PHPUnit\HasPregCapturesTrait
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
     * @dataProvider provHasPregCaptures
     *
     * @param mixed $actual
     */
    public function testAssertHasPregCapturesSucceeds(array $expect, $actual, string $message): void
    {
        self::assertHasPregCaptures($expect, $actual);
    }

    /**
     * @dataProvider provNotHasPregCaptures
     *
     * @param mixed $actual
     */
    public function testAssertHasPregCapturesFails(array $expect, $actual, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(sprintf('Failed asserting that %s.', $message));

        self::assertHasPregCaptures($expect, $actual);
    }

    /**
     * @dataProvider provNotHasPregCaptures
     *
     * @param mixed $actual
     */
    public function testAssertNotHasPregCaptureSucceeds(array $expect, $actual, string $message): void
    {
        self::assertNotHasPregCaptures($expect, $actual);
    }

    /**
     * @dataProvider provHasPregCaptures
     *
     * @param mixed $actual
     */
    public function testAssertNotHasPregCaptureFails(array $expect, $actual, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(sprintf('Failed asserting that %s.', $message));
        self::assertNotHasPregCaptures($expect, $actual);
    }

    /**
     * @dataProvider provHasPregCaptures
     *
     * @param mixed $actual
     */
    public function testHasPregCapturesSucceeds(array $expect, $actual, string $message): void
    {
        self::assertThat($actual, self::hasPregCaptures($expect));
    }

    /**
     * @dataProvider provNotHasPregCaptures
     * @dataProvider provNotHasPregCapturesNonArray
     *
     * @param mixed $actual
     */
    public function testHasPregCapturesFails(array $expect, $actual, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(sprintf('Failed asserting that %s.', $message));

        self::assertThat($actual, self::hasPregCaptures($expect));
    }
}

// vim: syntax=php sw=4 ts=4 et:
