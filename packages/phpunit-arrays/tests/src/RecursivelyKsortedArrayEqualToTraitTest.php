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
 * @covers \Tailors\PHPUnit\RecursivelyKsortedArrayEqualToTrait
 * @covers \Tailors\PHPUnit\RecursivelyKsortedArrayEqualToTraitTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursivelyKsortedArrayEqualToTraitTest extends TestCase
{
    use RecursivelyKsortedArrayEqualToTrait;
    use ProvRecursivelyKsortedArrayTrait;

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provRecursivelyKsortedArrayEqualToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provRecursivelyKsortedArrayIdenticalTo();

            yield from self::provRecursivelyKsortedArrayEqualButNotIdenticalTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'expect' => $case['expect'],
                'actual' => $case['actual'],
            ];
        }
    }

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provNotRecursivelyKsortedArrayEqualToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provRecursivelyKsortedArrayNotEqualTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'expect' => $case['expect'],
                'actual' => $case['actual'],
            ];
        }
    }

    /**
     * @dataProvider provRecursivelyKsortedArrayEqualToSucceeds
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testRecursivelyKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::recursivelyKsortedArrayEqualTo($expect));
    }

    /**
     * @dataProvider provRecursivelyKsortedArrayEqualToSucceeds
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertRecursivelyKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertRecursivelyKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provNotRecursivelyKsortedArrayEqualToSucceeds
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLogicalNotRecursivelyKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::recursivelyKsortedArrayEqualTo($expect)));
    }

    /**
     * @dataProvider provNotRecursivelyKsortedArrayEqualToSucceeds
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotRecursivelyKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertNotRecursivelyKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provRecursivelyKsortedArrayNotEqualTo
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertRecursivelyKsortedArrayEqualToFails(array $expect, $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an array '.
            'equal to the specified one when ksorted recursively./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        self::assertRecursivelyKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provRecursivelyKsortedArrayIdenticalTo
     * @dataProvider provRecursivelyKsortedArrayEqualButNotIdenticalTo
     *
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotRecursivelyKsortedArrayEqualToFails(array $expect, $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be an array '.
            'equal to the specified one when ksorted recursively./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        self::assertNotRecursivelyKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
