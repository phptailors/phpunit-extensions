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
 *
 * @psalm-type CtorArgs = list{0: array, 1?: int}
 */
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
     * @dataProvider provKsortedArrayEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testKsortedArrayEqualToSucceeds(array $expect, mixed $actual): void
    {
        self::assertThat($actual, self::ksortedArrayEqualTo($expect));
    }

    /**
     * @dataProvider provKsortedArrayEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertKsortedArrayEqualToSucceeds(array $expect, mixed $actual): void
    {
        self::assertKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provNotKsortedArrayEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLogicalNotKsortedArrayEqualToSucceeds(array $expect, mixed $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::ksortedArrayEqualTo($expect)));
    }

    /**
     * @dataProvider provNotKsortedArrayEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotKsortedArrayEqualToSucceeds(array $expect, mixed $actual): void
    {
        self::assertNotKsortedArrayEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertKsortedArrayEqualToFails(array $expect, mixed $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an array '.
            'equal to the specified one when ksorted./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertKsortedArrayEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotKsortedArrayEqualToFails(array $expect, mixed $actual, string $string): void
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
