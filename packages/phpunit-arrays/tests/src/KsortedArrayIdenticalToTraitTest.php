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
 * @covers \Tailors\PHPUnit\KsortedArrayIdenticalToTrait
 * @covers \Tailors\PHPUnit\KsortedArrayIdenticalToTraitTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs = list{0: array, 1?: int}
 */
final class KsortedArrayIdenticalToTraitTest extends TestCase
{
    use KsortedArrayIdenticalToTrait;
    use ProvKsortedArrayTrait;

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed}>
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
            ];
        }
    }

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed}>
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
            ];
        }
    }

    /**
     * @dataProvider provKsortedArrayIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual): void
    {
        self::assertThat($actual, self::ksortedArrayIdenticalTo($expect));
    }

    /**
     * @dataProvider provKsortedArrayIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual): void
    {
        self::assertKsortedArrayIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provNotKsortedArrayIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::ksortedArrayIdenticalTo($expect)));
    }

    /**
     * @dataProvider provNotKsortedArrayIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotKsortedArrayIdenticalToSucceeds(array $expect, mixed $actual): void
    {
        self::assertNotKsortedArrayIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provKsortedArrayNotEqualTo
     * @dataProvider provKsortedArrayEqualButNotIdenticalTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertKsortedArrayIdenticalToFails(array $expect, mixed $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an array '.
            'identical to the specified one when ksorted./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        self::assertKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provKsortedArrayIdenticalTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotKsortedArrayIdenticalToFails(array $expect, mixed $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be an array '.
            'identical to the specified one when ksorted./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        self::assertNotKsortedArrayIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
