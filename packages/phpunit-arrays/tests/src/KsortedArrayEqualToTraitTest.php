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
 *
 * @psalm-type CtorArgs = list{0: array, 1?: int}
 */
#[CoversClass(KsortedArrayEqualToTraitTest::class)]
#[Small]
final class KsortedArrayEqualToTraitTest extends TestCase
{
    use KsortedArrayEqualToTrait;
    use ProvKsortedArrayTrait;

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed}>
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
            ];
        }
    }

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed}>
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
    #[DataProvider('provKsortedArrayEqualToSucceeds')]
    public function testKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::ksortedArrayEqualTo($expect));
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provKsortedArrayEqualToSucceeds')]
    public function testAssertKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotKsortedArrayEqualToSucceeds')]
    public function testLogicalNotKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::ksortedArrayEqualTo($expect)));
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotKsortedArrayEqualToSucceeds')]
    public function testAssertNotKsortedArrayEqualToSucceeds(array $expect, $actual): void
    {
        self::assertNotKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provKsortedArrayNotEqualTo')]
    public function testAssertKsortedArrayEqualToFails(array $expect, $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an array '.
            'equal to the specified one when ksorted./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provKsortedArrayIdenticalTo')]
    #[DataProvider('provKsortedArrayEqualButNotIdenticalTo')]
    public function testAssertNotKsortedArrayEqualToFails(array $expect, $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be an array '.
            'equal to the specified one when ksorted./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
