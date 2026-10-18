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
use Tailors\PHPUnit\Constraint\ProvArrayValuesTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArrayValuesEqualToTrait
 * @covers \Tailors\PHPUnit\ArrayValuesEqualToTraitTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class ArrayValuesEqualToTraitTest extends TestCase
{
    use ArrayValuesEqualToTrait;
    use ProvArrayValuesTrait;

    /**
     * @psalm-return iterable<string, array{expect: array, actual: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provArrayValuesEqualToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provArrayValuesIdenticalTo();

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
     * @psalm-return iterable<string, array{expect: array, actual: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provNotArrayValuesEqualToSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provArrayValuesNotEqualTo();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'expect' => $case['expect'],
                'actual' => $case['actual'],
            ];
        }
    }

    /**
     * @dataProvider provArrayValuesEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testArrayValuesEqualToSucceeds(array $expect, mixed $actual): void
    {
        self::assertThat($actual, self::arrayValuesEqualTo($expect));
    }

    /**
     * @dataProvider provArrayValuesEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertArrayValuesEqualToSucceeds(array $expect, mixed $actual): void
    {
        self::assertArrayValuesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provNotArrayValuesEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testLogicalNotArrayValuesEqualToSucceeds(array $expect, mixed $actual): void
    {
        self::assertThat($actual, self::logicalNot(self::arrayValuesEqualTo($expect)));
    }

    /**
     * @dataProvider provNotArrayValuesEqualToSucceeds
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotArrayValuesEqualToSucceeds(array $expect, mixed $actual): void
    {
        self::assertNotArrayValuesEqualTo($expect, $actual);
    }

    /**
     * @dataProvider provArrayValuesNotEqualTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertArrayValuesEqualToFails(array $expect, mixed $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' is an array or ArrayAccess '.
            'with values equal to the specified ones./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        self::assertArrayValuesEqualTo($expect, $actual, 'Lorem ipsum.');
    }

    /**
     * @dataProvider provArrayValuesIdenticalTo
     * @dataProvider provArrayValuesEqualButNotIdenticalTo
     *
     * @psalm-param array $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotArrayValuesEqualToFails(array $expect, mixed $actual, string $string): void
    {
        $regexp = '/^Lorem ipsum.\n'.
            'Failed asserting that '.preg_quote($string, '/').' fails to be an array or ArrayAccess '.
            'with values equal to the specified ones./';
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches($regexp);

        self::assertNotArrayValuesEqualTo($expect, $actual, 'Lorem ipsum.');
    }
}

// vim: syntax=php sw=4 ts=4 et:
