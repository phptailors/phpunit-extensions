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
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\Constraint\ProvObjectPropertiesTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ObjectPropertiesEqualToTrait
 * @covers \Tailors\PHPUnit\ObjectPropertiesEqualToTraitTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type CtorArgs = list{ArrayLike}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class ObjectPropertiesEqualToTraitTest extends TestCase
{
    use ObjectPropertiesEqualToTrait;
    use ProvObjectPropertiesTrait;

    /**
     * @psalm-return iterable<string, array{expect: array, actual: object}>
     *
     * @codeCoverageIgnore
     */
    public static function provObjectPropertiesEqualToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provObjectPropertiesIdenticalTo();

            yield from self::provObjectPropertiesEqualButNotIdenticalTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'expect' => $case['expect'],
                'actual' => $case['actual'],
            ];
        }
    }

    /**
     * @psalm-return iterable<string, array{expect: array, actual: object}>
     *
     * @codeCoverageIgnore
     */
    public static function provNotObjectPropertiesEqualToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provObjectPropertiesNotEqualTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'expect' => $case['expect'],
                'actual' => $case['actual'],
            ];
        }
    }

    /**
     * @dataProvider provObjectPropertiesEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testObjectPropertiesEqualToSucceeds(array $expect, object $actual): void
    {
        self::assertThat($actual, self::objectPropertiesEqualTo($expect));
    }

    /**
     * @dataProvider provObjectPropertiesEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertObjectPropertiesEqualToSucceeds(array $expect, object $actual): void
    {
        self::assertObjectPropertiesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provNotObjectPropertiesEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLogicalNotObjectPropertiesEqualToSucceds(array $expect, object $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::objectPropertiesEqualTo($expect)));
    }

    /**
     * @dataProvider provNotObjectPropertiesEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotObjectPropertiesEqualToSucceeds(array $expect, object $actual): void
    {
        self::assertNotObjectPropertiesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provObjectPropertiesNotEqualTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertObjectPropertiesEqualToFails(array $expect, object $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an object '.
            'with properties equal to the specified ones./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertObjectPropertiesEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provObjectPropertiesIdenticalTo
     * @dataProvider provObjectPropertiesEqualButNotIdenticalTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotObjectPropertiesEqualToFails(array $expect, object $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be an object '.
            'with properties equal to the specified ones./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotObjectPropertiesEqualTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
