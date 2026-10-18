<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(ReferenceStorage::class)]
#[CoversClass(ReferenceStorageTest::class)]
#[Small]
final class ReferenceStorageTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCountOnFreshObject(): void
    {
        $storage = new ReferenceStorage();

        self::assertSame(0, count($storage));
    }

    /**
     * @psalm-param array $values
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provAddAndCount')]
    public function testAddAndCount(array $values, int $expect): void
    {
        $storage = new ReferenceStorage();

        /** @psalm-var mixed $value */
        foreach ($values as &$value) {
            $storage->add($value);
        }

        self::assertSame($expect, count($storage));
    }

    /**
     * @psalm-param array $values
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provAddRemoveContains')]
    public function testAddRemoveContains(array $values): void
    {
        $storage = new ReferenceStorage();

        /** @psalm-var mixed $value */
        foreach ($values as &$value) {
            self::assertFalse($storage->contains($value));
            $storage->add($value);
            self::assertTrue($storage->contains($value));
        }

        /** @psalm-var mixed $value */
        foreach ($values as &$value) {
            $storage->remove($value);
            self::assertFalse($storage->contains($value));
        }
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testWithAliasedVariables(): void
    {
        $storage = new ReferenceStorage();

        $var1 = 'var1';
        $var2 = &$var1;

        $storage->add($var1);

        self::assertTrue($storage->contains($var1));
        self::assertTrue($storage->contains($var2));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testWithAliasedArray(): void
    {
        $storage = new ReferenceStorage();

        $arr1 = [];
        $arr2 = [];
        $arr3 = [];
        $arr1['arr2'] = &$arr2;
        $arr1['arr3'] = &$arr3;
        $arr2['arr1'] = &$arr1;
        $arr2['arr3'] = &$arr3;
        $arr3['arr1'] = $arr1;
        $arr3['arr2'] = $arr2;

        $storage->add($arr1);
        self::assertTrue($storage->contains($arr1));
        self::assertFalse($storage->contains($arr1['arr2']));
        self::assertFalse($storage->contains($arr1['arr3']));
        self::assertFalse($storage->contains($arr2));
        self::assertTrue($storage->contains($arr2['arr1']));
        self::assertFalse($storage->contains($arr2['arr3']));
        self::assertFalse($storage->contains($arr3));
        self::assertFalse($storage->contains($arr3['arr1']));
        self::assertFalse($storage->contains($arr3['arr2']));

        $storage->add($arr2);
        self::assertTrue($storage->contains($arr1));
        self::assertTrue($storage->contains($arr1['arr2']));
        self::assertFalse($storage->contains($arr1['arr3']));
        self::assertTrue($storage->contains($arr2));
        self::assertTrue($storage->contains($arr2['arr1']));
        self::assertFalse($storage->contains($arr2['arr3']));
        self::assertFalse($storage->contains($arr3));
        self::assertFalse($storage->contains($arr3['arr1']));
        self::assertFalse($storage->contains($arr3['arr2']));

        $storage->add($arr3);
        self::assertTrue($storage->contains($arr1));
        self::assertTrue($storage->contains($arr1['arr2']));
        self::assertTrue($storage->contains($arr1['arr3']));
        self::assertTrue($storage->contains($arr2));
        self::assertTrue($storage->contains($arr2['arr1']));
        self::assertTrue($storage->contains($arr2['arr3']));
        self::assertTrue($storage->contains($arr3));
        self::assertFalse($storage->contains($arr3['arr1']));
        self::assertFalse($storage->contains($arr3['arr2']));
        self::assertTrue($storage->contains($arr3['arr1']['arr2']));
        self::assertTrue($storage->contains($arr3['arr1']['arr3']));
        self::assertTrue($storage->contains($arr3['arr2']['arr1']));
        self::assertTrue($storage->contains($arr3['arr2']['arr3']));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testWithAliasedArray2(): void
    {
        $storage = new ReferenceStorage();

        $arr = ['foo' => []];
        $arr['foo']['bar'] = &$arr;

        $storage->add($arr);
        self::assertTrue($storage->contains($arr));
        self::assertFalse($storage->contains($arr['foo']));
        self::assertTrue($storage->contains($arr['foo']['bar']));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     * @psalm-suppress RedundantConditionGivenDocblockType
     */
    public function testDoesNotMessUpData(): void
    {
        $storage = new ReferenceStorage();

        $var = 'var';
        $storage->add($var);
        self::assertSame('var', $var);

        self::assertTrue($storage->contains($var));
        self::assertSame('var', $var);

        $storage->remove($var);
        self::assertSame('var', $var);
    }

    /**
     * @psalm-return iterable<string, array{values: array, expect: int}>
     *
     * @codeCoverageIgnore
     */
    public static function provAddAndCount(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'values' => [], 'expect' => 0,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'values' => ['a'], 'expect' => 1,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'values' => [new \stdClass()], 'expect' => 1,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'values' => ['a', new \stdClass()], 'expect' => 2,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'values' => ['a', new \stdClass(), ['x' => 'X', 'y' => ['z' => 'Z']]], 'expect' => 3,
        ];
    }

    /**
     * @psalm-return iterable<string, array{values: array}>
     *
     * @codeCoverageIgnore
     */
    public static function provAddRemoveContains(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'values' => ['a'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'values' => [new \stdClass()],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'values' => ['a', new \stdClass(), ['x' => 'X', 'y' => ['z' => 'Z']]],
        ];
    }
}
// vim: syntax=php sw=4 ts=4 et:
