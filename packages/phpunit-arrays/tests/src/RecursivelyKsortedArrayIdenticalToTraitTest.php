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
#[CoversClass(RecursivelyKsortedArrayIdenticalToTraitTest::class)]
#[Small]
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
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provRecursivelyKsortedArrayIdenticalToSucceeds')]
    public function testRecursivelyKsortedArrayIdenticalToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::recursivelyKsortedArrayIdenticalTo($expect));
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provRecursivelyKsortedArrayIdenticalToSucceeds')]
    public function testAssertRecursivelyKsortedArrayIdenticalToSucceeds(array $expect, $actual): void
    {
        self::assertRecursivelyKsortedArrayIdenticalTo($expect, $actual);
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotRecursivelyKsortedArrayIdenticalToSucceeds')]
    public function testNotRecursivelyKsortedArrayIdenticalToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::recursivelyKsortedArrayIdenticalTo($expect)));
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotRecursivelyKsortedArrayIdenticalToSucceeds')]
    public function testAssertNotRecursivelyKsortedArrayIdenticalToSucceeds(array $expect, $actual): void
    {
        self::assertNotRecursivelyKsortedArrayIdenticalTo($expect, $actual);
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provRecursivelyKsortedArrayNotEqualTo')]
    #[DataProvider('provRecursivelyKsortedArrayEqualButNotIdenticalTo')]
    public function testAssertRecursivelyKsortedArrayIdenticalToFails(array $expect, $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an array '.
            'identical to the specified one when ksorted recursively./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertRecursivelyKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provRecursivelyKsortedArrayIdenticalTo')]
    public function testAssertNotRecursivelyKsortedArrayIdenticalToFails(array $expect, $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be an array '.
            'identical to the specified one when ksorted recursively./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotRecursivelyKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
