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
use Tailors\PHPUnit\Constraint\ProvKsortedArrayTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\KsortedArrayEqualToTrait
 * @covers \Tailors\PHPUnit\KsortedArrayEqualToTraitTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class KsortedArrayEqualToTraitTest extends TestCase
{
    use KsortedArrayEqualToTrait;
    use ProvKsortedArrayTrait;

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed, flags?: int}>
     *
     * @codeCoverageIgnore
     */
    public static function provKsortedArrayEqualToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provKsortedArrayIdenticalTo();

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
     * @psalm-return iterable<string, array{expect: array, actual: mixed, flags?: int}>
     *
     * @codeCoverageIgnore
     */
    public static function provNotKsortedArrayEqualToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provKsortedArrayNotEqualTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'expect' => $case['expect'],
                'actual' => $case['actual'],
            ] + (array_key_exists('flags', $case) ? ['flags' => $case['flags']] : []);
        }
    }

    /**
     * @dataProvider provKsortedArrayEqualToSucceeds
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testKsortedArrayEqualToSucceeds(array $expect, $actual, ?int $flags = null): void
    {
        if (null === $flags) {
            self::assertThat($actual, self::ksortedArrayEqualTo($expect));
        } else {
            self::assertThat($actual, self::ksortedArrayEqualTo($expect, $flags));
        }
    }

    /**
     * @dataProvider provKsortedArrayEqualToSucceeds
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertKsortedArrayEqualToSucceeds(array $expect, $actual, ?int $flags = null): void
    {
        if (null === $flags) {
            self::assertKsortedArrayEqualTo($expect, $actual);
        } else {
            self::assertKsortedArrayEqualTo($expect, $actual, '', $flags);
        }
    }

    /**
     * @dataProvider provNotKsortedArrayEqualToSucceeds
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLogicalNotKsortedArrayEqualToSucceeds(array $expect, $actual, ?int $flags = null): void
    {
        if (null === $flags) {
            self::assertThat($actual, self::logicalNot(self::ksortedArrayEqualTo($expect)));
        } else {
            self::assertThat($actual, self::logicalNot(self::ksortedArrayEqualTo($expect, $flags)));
        }
    }

    /**
     * @dataProvider provNotKsortedArrayEqualToSucceeds
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotKsortedArrayEqualToSucceeds(array $expect, $actual, ?int $flags = null): void
    {
        if (null === $flags) {
            self::assertNotKsortedArrayEqualTo($expect, $actual);
        } else {
            self::assertNotKsortedArrayEqualTo($expect, $actual, '', $flags);
        }
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertKsortedArrayEqualToFails(array $expect, $actual, string $string, ?int $flags = null): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an array '.
            'equal to the specified one when ksorted./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        if (null === $flags) {
            self::assertKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.');
        } else {
            self::assertKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.', $flags);
        }
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotKsortedArrayEqualToFails(array $expect, $actual, string $string, ?int $flags = null): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be an array '.
            'equal to the specified one when ksorted./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        if (null === $flags) {
            self::assertNotKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.');
        } else {
            self::assertNotKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.', $flags);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
