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
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\Constraint\ProvObjectPropertiesTrait;

/**
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
#[CoversClass(ObjectPropertiesIdenticalToTraitTest::class)]
#[Small]
final class ObjectPropertiesIdenticalToTraitTest extends TestCase
{
    use ObjectPropertiesIdenticalToTrait;
    use ProvObjectPropertiesTrait;

    /**
     * @psalm-return iterable<string, array{expect: array, actual: object}>
     *
     * @codeCoverageIgnore
     */
    public static function provObjectPropertiesIdenticalToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provObjectPropertiesIdenticalTo();
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
    public static function provNotObjectPropertiesIdenticalToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provObjectPropertiesNotEqualTo();

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
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provObjectPropertiesIdenticalToSucceeds')]
    public function testObjectPropertiesIdenticalToSucceeds(array $expect, object $actual): void
    {
        self::assertThat($actual, self::objectPropertiesIdenticalTo($expect));
    }

    /**
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provObjectPropertiesIdenticalToSucceeds')]
    public function testAssertObjectPropertiesIdenticalToSucceeds(array $expect, object $actual): void
    {
        self::assertObjectPropertiesIdenticalTo($expect, $actual);
    }

    /**
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotObjectPropertiesIdenticalToSucceeds')]
    public function testLogicalNotObjectPropertiesIdenticalToSucceeds(array $expect, object $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::objectPropertiesIdenticalTo($expect)));
    }

    /**
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotObjectPropertiesIdenticalToSucceeds')]
    public function testAssertNotObjectPropertiesIdenticalToSucceeds(array $expect, object $actual): void
    {
        self::assertNotObjectPropertiesIdenticalTo($expect, $actual);
    }

    /**
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provObjectPropertiesNotEqualTo')]
    #[DataProvider('provObjectPropertiesEqualButNotIdenticalTo')]
    public function testAssertObjectPropertiesIdenticalToFails(array $expect, object $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an object '.
            'with properties identical to the specified ones./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertObjectPropertiesIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provObjectPropertiesIdenticalTo')]
    public function testAssertNotObjectPropertiesIdenticalToFails(array $expect, object $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be an object '.
            'with properties identical to the specified ones./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotObjectPropertiesIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
