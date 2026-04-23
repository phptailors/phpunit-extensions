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
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ProvHasPregCapturesTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(ProvHasPregCapturesTrait::class)]
#[CoversClass(HasPregCapturesTrait::class)]
#[Small]
final class HasPregCapturesTraitTest extends TestCase
{
    use HasPregCapturesTrait;
    use ProvHasPregCapturesTrait;

    #[DataProvider('provHasPregCaptures')]
    public function testAssertHasPregCapturesSucceeds(array $expect, mixed $actual, string $message): void
    {
        self::assertHasPregCaptures($expect, $actual);
    }

    #[DataProvider('provNotHasPregCaptures')]
    public function testAssertHasPregCapturesFails(array $expect, mixed $actual, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(sprintf('Failed asserting that %s.', $message));

        self::assertHasPregCaptures($expect, $actual);
    }

    #[DataProvider('provNotHasPregCaptures')]
    public function testAssertNotHasPregCaptureSucceeds(array $expect, mixed $actual, string $message): void
    {
        self::assertNotHasPregCaptures($expect, $actual);
    }

    #[DataProvider('provHasPregCaptures')]
    public function testAssertNotHasPregCaptureFails(array $expect, mixed $actual, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(sprintf('Failed asserting that %s.', $message));
        self::assertNotHasPregCaptures($expect, $actual);
    }

    #[DataProvider('provHasPregCaptures')]
    public function testHasPregCapturesSucceeds(array $expect, mixed $actual, string $message): void
    {
        self::assertThat($actual, self::hasPregCaptures($expect));
    }

    #[DataProvider('provNotHasPregCaptures')]
    #[DataProvider('provNotHasPregCapturesNonArray')]
    public function testHasPregCapturesFails(array $expect, mixed $actual, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(sprintf('Failed asserting that %s.', $message));

        self::assertThat($actual, self::hasPregCaptures($expect));
    }
}

// vim: syntax=php sw=4 ts=4 et:
