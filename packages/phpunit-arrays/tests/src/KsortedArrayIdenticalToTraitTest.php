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
use Tailors\PHPUnit\Constraint\ProvKsortedArrayTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(KsortedArrayIdenticalToTraitTest::class)]
#[Small]
final class KsortedArrayIdenticalToTraitTest extends TestCase
{
    use KsortedArrayIdenticalToTrait;
    use ProvKsortedArrayTrait;

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed, flags?: int}>
     *
     * @codeCoverageIgnore
     */
    public static function provKsortedArrayIdenticalToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provKsortedArrayIdenticalTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'expect' => $case['expect'],
                'actual' => $case['actual'],
            ] + (array_key_exists('flags', $case) ? ['flags' => $case['flags']] : []);
        }
    }

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed, flags?: int}>
     *
     * @codeCoverageIgnore
     */
    public static function provNotKsortedArrayIdenticalToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provKsortedArrayNotEqualTo();

            yield from self::provKsortedArrayEqualButNotIdenticalTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'expect' => $case['expect'],
                'actual' => $case['actual'],
            ] + (array_key_exists('flags', $case) ? ['flags' => $case['flags']] : []);
        }
    }

    /**
     *
     * @psalm-param array $expect
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provKsortedArrayIdenticalToSucceeds')]
    public function testKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual, ?int $flags = null): void
    {
        if (null === $flags) {
            self::assertThat($actual, self::ksortedArrayIdenticalTo($expect));
        } else {
            self::assertThat($actual, self::ksortedArrayIdenticalTo($expect, $flags));
        }
    }

    /**
     *
     * @psalm-param array $expect
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provKsortedArrayIdenticalToSucceeds')]
    public function testAssertKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual, ?int $flags = null): void
    {
        if (null === $flags) {
            self::assertKsortedArrayIdenticalTo($expect, $actual);
        } else {
            self::assertKsortedArrayIdenticalTo($expect, $actual, '', $flags);
        }
    }

    /**
     *
     * @psalm-param array $expect
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotKsortedArrayIdenticalToSucceeds')]
    public function testNotKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual, ?int $flags = null): void
    {
        if (null === $flags) {
            self::assertThat($actual, self::logicalNot(self::ksortedArrayIdenticalTo($expect)));
        } else {
            self::assertThat($actual, self::logicalNot(self::ksortedArrayIdenticalTo($expect, $flags)));
        }
    }

    /**
     *
     * @psalm-param array $expect
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotKsortedArrayIdenticalToSucceeds')]
    public function testAssertNotKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual, ?int $flags = null): void
    {
        if (null === $flags) {
            self::assertNotKsortedArrayIdenticalTo($expect, $actual);
        } else {
            self::assertNotKsortedArrayIdenticalTo($expect, $actual, '', $flags);
        }
    }

    /**
     *
     * @psalm-param array $expect
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provKsortedArrayNotEqualTo')]
    #[DataProvider('provKsortedArrayEqualButNotIdenticalTo')]
    public function testAssertKsortedArrayIdenticalToFails(array $expect, mixed $actual, string $string, ?int $flags = null): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an array '.
            'identical to the specified one when ksorted./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        if (null === $flags) {
            self::assertKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.');
        } else {
            self::assertKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.', $flags);
        }
    }

    /**
     *
     * @psalm-param array $expect
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provKsortedArrayIdenticalTo')]
    public function testAssertNotKsortedArrayIdenticalToFails(array $expect, mixed $actual, string $string, ?int $flags = null): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be an array '.
            'identical to the specified one when ksorted./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        if (null === $flags) {
            self::assertNotKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.');
        } else {
            self::assertNotKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.', $flags);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
