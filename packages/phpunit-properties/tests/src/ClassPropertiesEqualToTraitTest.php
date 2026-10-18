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
use Tailors\PHPUnit\Constraint\ProvClassPropertiesTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ClassPropertiesEqualToTrait
 * @covers \Tailors\PHPUnit\ClassPropertiesEqualToTraitTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ClassPropertiesEqualToTraitTest extends TestCase
{
    use ClassPropertiesEqualToTrait;
    use ProvClassPropertiesTrait;

    /**
     * @psalm-return iterable<string, array{expect: array, actual: string}>
     *
     * @codeCoverageIgnore
     */
    public static function provClassPropertiesEqualToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provClassPropertiesIdenticalTo();

            yield from self::provClassPropertiesEqualButNotIdenticalTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'expect' => $case['expect'],
                'actual' => $case['actual'],
            ];
        }
    }

    /**
     * @psalm-return iterable<string, array{expect: array, actual: string}>
     *
     * @codeCoverageIgnore
     */
    public static function provNotClassPropertiesEqualToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provClassPropertiesNotEqualTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'expect' => $case['expect'],
                'actual' => $case['actual'],
            ];
        }
    }

    /**
     * @dataProvider provClassPropertiesEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testClassPropertiesEqualToSucceeds(array $expect, string $actual): void
    {
        self::assertThat($actual, self::classPropertiesEqualTo($expect));
    }

    /**
     * @dataProvider provClassPropertiesEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertClassPropertiesEqualToSucceeds(array $expect, string $actual): void
    {
        self::assertClassPropertiesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provNotClassPropertiesEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotClassPropertiesEqualToSucceeds(array $expect, string $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::classPropertiesEqualTo($expect)));
    }

    /**
     * @dataProvider provNotClassPropertiesEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotClassPropertiesEqualToSucceeds(array $expect, string $actual): void
    {
        self::assertNotClassPropertiesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertClassPropertiesEqualToFails(array $expect, string $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is a class '.
            'with properties equal to the specified ones./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        self::assertClassPropertiesEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotClassPropertiesEqualToFails(array $expect, string $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be a class '.
            'with properties equal to the specified ones./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        self::assertNotClassPropertiesEqualTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
