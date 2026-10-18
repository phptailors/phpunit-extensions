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
use Tailors\PHPUnit\Constraint\ProvRecursivelyKsortedArrayTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs = list{0: array, 1?: int}
 */
#[CoversClass(RecursivelyKsortedArrayEqualToTraitTest::class)]
#[Small]
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
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provRecursivelyKsortedArrayEqualToSucceeds')]
    public function testRecursivelyKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::recursivelyKsortedArrayEqualTo($expect));
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provRecursivelyKsortedArrayEqualToSucceeds')]
    public function testAssertRecursivelyKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertRecursivelyKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotRecursivelyKsortedArrayEqualToSucceeds')]
    public function testLogicalNotRecursivelyKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::recursivelyKsortedArrayEqualTo($expect)));
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotRecursivelyKsortedArrayEqualToSucceeds')]
    public function testAssertNotRecursivelyKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertNotRecursivelyKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provRecursivelyKsortedArrayNotEqualTo')]
    public function testAssertRecursivelyKsortedArrayEqualToFails(array $expect, $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an array '.
            'equal to the specified one when ksorted recursively./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertRecursivelyKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provRecursivelyKsortedArrayIdenticalTo')]
    #[DataProvider('provRecursivelyKsortedArrayEqualButNotIdenticalTo')]
    public function testAssertNotRecursivelyKsortedArrayEqualToFails(array $expect, $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be an array '.
            'equal to the specified one when ksorted recursively./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotRecursivelyKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
