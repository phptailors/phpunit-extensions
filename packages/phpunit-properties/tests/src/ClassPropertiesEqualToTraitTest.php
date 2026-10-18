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
use Tailors\PHPUnit\Constraint\ProvClassPropertiesTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(ClassPropertiesEqualToTraitTest::class)]
#[Small]
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
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provClassPropertiesEqualToSucceeds')]
    public function testClassPropertiesEqualToSucceeds(array $expect, string $actual): void
    {
        self::assertThat($actual, self::classPropertiesEqualTo($expect));
    }

    /**
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provClassPropertiesEqualToSucceeds')]
    public function testAssertClassPropertiesEqualToSucceeds(array $expect, string $actual): void
    {
        self::assertClassPropertiesEqualTo($expect, $actual);
    }

    /**
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotClassPropertiesEqualToSucceeds')]
    public function testNotClassPropertiesEqualToSucceeds(array $expect, string $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::classPropertiesEqualTo($expect)));
    }

    /**
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotClassPropertiesEqualToSucceeds')]
    public function testAssertNotClassPropertiesEqualToSucceeds(array $expect, string $actual): void
    {
        self::assertNotClassPropertiesEqualTo($expect, $actual);
    }

    /**
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provClassPropertiesNotEqualTo')]
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
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provClassPropertiesIdenticalTo')]
    #[DataProvider('provClassPropertiesEqualButNotIdenticalTo')]
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
