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
 * @covers \Tailors\PHPUnit\ClassPropertiesIdenticalToTrait
 * @covers \Tailors\PHPUnit\ClassPropertiesIdenticalToTraitTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ClassPropertiesIdenticalToTraitTest extends TestCase
{
    use ClassPropertiesIdenticalToTrait;
    use ProvClassPropertiesTrait;

    /**
     * @psalm-return iterable<string, array{expect: array, actual: string}>
     *
     * @codeCoverageIgnore
     */
    public static function provClassPropertiesIdenticalToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provClassPropertiesIdenticalTo();
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
    public static function provNotClassPropertiesIdenticalToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provClassPropertiesNotEqualTo();

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
     * @dataProvider provClassPropertiesIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testClassPropertiesIdenticalToSucceeds(array $expect, string $actual): void
    {
        self::assertThat($actual, self::classPropertiesIdenticalTo($expect));
    }

    /**
     * @dataProvider provClassPropertiesIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertClassPropertiesIdenticalToSucceeds(array $expect, string $actual): void
    {
        self::assertClassPropertiesIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provNotClassPropertiesIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLogicalNotClassPropertiesIdenticalToSucceeds(array $expect, string $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::classPropertiesIdenticalTo($expect)));
    }

    /**
     * @dataProvider provNotClassPropertiesIdenticalToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotClassPropertiesIdenticalToSucceeds(array $expect, string $actual): void
    {
        self::assertNotClassPropertiesIdenticalTo($expect, $actual);
    }

    /**
     * @dataProvider provClassPropertiesNotEqualTo
     * @dataProvider provClassPropertiesEqualButNotIdenticalTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertClassPropertiesIdenticalToFails(array $expect, string $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is a class '.
            'with properties identical to the specified ones./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        self::assertClassPropertiesIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provClassPropertiesIdenticalTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotClassPropertiesIdenticalToFails(array $expect, string $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be a class '.
            'with properties identical to the specified ones./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        self::assertNotClassPropertiesIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
