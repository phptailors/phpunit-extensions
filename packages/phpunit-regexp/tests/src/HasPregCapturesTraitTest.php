<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ProvHasPregCapturesTrait;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(ProvHasPregCapturesTrait::class)]
#[CoversClass(HasPregCapturesTrait::class)]
final class HasPregCapturesTraitTest extends TestCase
{
    use HasPregCapturesTrait;
    use ProvHasPregCapturesTrait;

    /**
     * @param mixed $actual
     */
    #[DataProvider('provHasPregCaptures')]
    public function testAssertHasPregCapturesSucceeds(array $expect, $actual, string $message): void
    {
        self::assertHasPregCaptures($expect, $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provNotHasPregCaptures')]
    public function testAssertHasPregCapturesFails(array $expect, $actual, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(sprintf('Failed asserting that %s.', $message));

        self::assertHasPregCaptures($expect, $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provNotHasPregCaptures')]
    public function testAssertNotHasPregCaptureSucceeds(array $expect, $actual, string $message): void
    {
        self::assertNotHasPregCaptures($expect, $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provHasPregCaptures')]
    public function testAssertNotHasPregCaptureFails(array $expect, $actual, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(sprintf('Failed asserting that %s.', $message));
        self::assertNotHasPregCaptures($expect, $actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provHasPregCaptures')]
    public function testHasPregCapturesSucceeds(array $expect, $actual, string $message): void
    {
        self::assertThat($actual, self::hasPregCaptures($expect));
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provNotHasPregCaptures')]
    #[DataProvider('provNotHasPregCapturesNonArray')]
    public function testHasPregCapturesFails(array $expect, $actual, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(sprintf('Failed asserting that %s.', $message));

        self::assertThat($actual, self::hasPregCaptures($expect));
    }
}

// vim: syntax=php sw=4 ts=4 et:
