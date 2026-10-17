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
use Tailors\PHPUnit\Constraint\ProvArrayValuesTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
#[CoversClass(ArrayValuesIdenticalToTraitTest::class)]
#[Small]
final class ArrayValuesIdenticalToTraitTest extends TestCase
{
    use ArrayValuesIdenticalToTrait;
    use ProvArrayValuesTrait;

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provArrayValuesIdenticalToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provArrayValuesIdenticalTo();
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
    public static function provNotArrayValuesIdenticalToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provArrayValuesNotEqualTo();

            yield from self::provArrayValuesEqualButNotIdenticalTo();
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
    #[DataProvider('provArrayValuesIdenticalToSucceeds')]
    public function testArrayValuesIdenticalToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::arrayValuesIdenticalTo($expect));
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provArrayValuesIdenticalToSucceeds')]
    public function testAssertArrayValuesIdenticalToSucceeds(array $expect, $actual): void
    {
        self::assertArrayValuesIdenticalTo($expect, $actual);
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotArrayValuesIdenticalToSucceeds')]
    public function testLogicalNotArrayValuesIdenticalToSucceeds(array $expect, $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::arrayValuesIdenticalTo($expect)));
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotArrayValuesIdenticalToSucceeds')]
    public function testAssertNotArrayValuesIdenticalToSucceeds(array $expect, $actual): void
    {
        self::assertNotArrayValuesIdenticalTo($expect, $actual);
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provArrayValuesNotEqualTo')]
    #[DataProvider('provArrayValuesEqualButNotIdenticalTo')]
    public function testAssertArrayValuesIdenticalToFails(array $expect, $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an array or ArrayAccess '.
            'with values identical to the specified ones./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertArrayValuesIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @param mixed $actual
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provArrayValuesIdenticalTo')]
    public function testAssertNotArrayValuesIdenticalToFails(array $expect, $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be an array or ArrayAccess '.
            'with values identical to the specified ones./';
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessageMatches($regexp);

        self::assertNotArrayValuesIdenticalTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
