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
 *
 * @psalm-type CtorArgs = list{0: array, 1?: int}
 */
final class RecursivelyKsortedArrayIdenticalToTraitTest extends TestCase
{
    use RecursivelyKsortedArrayIdenticalToTrait;
    use ProvRecursivelyKsortedArrayTrait;

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed}>
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
            ];
        }
    }

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed}>
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
            ];
        }
    }

    /**
     * @dataProvider provRecursivelyKsortedArrayIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testRecursivelyKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual): void
    {
        self::assertThat($actual, self::recursivelyKsortedArrayIdenticalTo($expect));
    }

    /**
     * @dataProvider provRecursivelyKsortedArrayIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertRecursivelyKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual): void
    {
        self::assertRecursivelyKsortedArrayIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provNotRecursivelyKsortedArrayIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotRecursivelyKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::recursivelyKsortedArrayIdenticalTo($expect)));
    }

    /**
     * @dataProvider provNotRecursivelyKsortedArrayIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotRecursivelyKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual): void
    {
        self::assertNotRecursivelyKsortedArrayIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provRecursivelyKsortedArrayNotEqualTo
     * @dataProvider provRecursivelyKsortedArrayEqualButNotIdenticalTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertRecursivelyKsortedArrayIdenticalToFails(array $expect, mixed $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an array '.
            'identical to the specified one when ksorted recursively./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        self::assertRecursivelyKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provRecursivelyKsortedArrayIdenticalTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotRecursivelyKsortedArrayIdenticalToFails(array $expect, mixed $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be an array '.
            'identical to the specified one when ksorted recursively./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        self::assertNotRecursivelyKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
