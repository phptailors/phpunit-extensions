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
use Tailors\PHPUnit\Constraint\ProvRecursivelyKsortedArrayTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursivelyKsortedArrayIdenticalToTrait
 * @covers \Tailors\PHPUnit\RecursivelyKsortedArrayIdenticalToTraitTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursivelyKsortedArrayIdenticalToTraitTest extends TestCase
{
    use RecursivelyKsortedArrayIdenticalToTrait;
    use ProvRecursivelyKsortedArrayTrait;

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed, flags?: int}>
     *
     * @codeCoverageIgnore
     */
    public static function provRecursivelyKsortedArrayIdenticalToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provRecursivelyKsortedArrayIdenticalTo();
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
    public static function provNotRecursivelyKsortedArrayIdenticalToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provRecursivelyKsortedArrayNotEqualTo();

            yield from self::provRecursivelyKsortedArrayEqualButNotIdenticalTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'expect' => $case['expect'],
                'actual' => $case['actual'],
            ] + (array_key_exists('flags', $case) ? ['flags' => $case['flags']] : []);
        }
    }

    /**
     * @dataProvider provRecursivelyKsortedArrayIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testRecursivelyKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual, ?int $flags = null): void
    {
        if (null === $flags) {
            self::assertThat($actual, self::recursivelyKsortedArrayIdenticalTo($expect));
        } else {
            self::assertThat($actual, self::recursivelyKsortedArrayIdenticalTo($expect, $flags));
        }
    }

    /**
     * @dataProvider provRecursivelyKsortedArrayIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertRecursivelyKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual, ?int $flags = null): void
    {
        if (null === $flags) {
            self::assertRecursivelyKsortedArrayIdenticalTo($expect, $actual);
        } else {
            self::assertRecursivelyKsortedArrayIdenticalTo($expect, $actual, '', $flags);
        }
    }

    /**
     * @dataProvider provNotRecursivelyKsortedArrayIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotRecursivelyKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual, ?int $flags = null): void
    {
        if (null === $flags) {
            self::assertThat($actual, self::logicalNot(self::recursivelyKsortedArrayIdenticalTo($expect)));
        } else {
            self::assertThat($actual, self::logicalNot(self::recursivelyKsortedArrayIdenticalTo($expect, $flags)));
        }
    }

    /**
     * @dataProvider provNotRecursivelyKsortedArrayIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotRecursivelyKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual, ?int $flags = null): void
    {
        if (null === $flags) {
            self::assertNotRecursivelyKsortedArrayIdenticalTo($expect, $actual);
        } else {
            self::assertNotRecursivelyKsortedArrayIdenticalTo($expect, $actual, '', $flags);
        }
    }

    /**
     * @dataProvider provRecursivelyKsortedArrayNotEqualTo
     * @dataProvider provRecursivelyKsortedArrayEqualButNotIdenticalTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertRecursivelyKsortedArrayIdenticalToFails(array $expect, mixed $actual, string $string, ?int $flags = null): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an array '.
            'identical to the specified one when ksorted recursively./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        if (null === $flags) {
            self::assertRecursivelyKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.');
        } else {
            self::assertRecursivelyKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.', $flags);
        }
    }

    /**
     * @dataProvider provRecursivelyKsortedArrayIdenticalTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotRecursivelyKsortedArrayIdenticalToFails(array $expect, mixed $actual, string $string, ?int $flags = null): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be an array '.
            'identical to the specified one when ksorted recursively./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        if (null === $flags) {
            self::assertNotRecursivelyKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.');
        } else {
            self::assertNotRecursivelyKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.', $flags);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
