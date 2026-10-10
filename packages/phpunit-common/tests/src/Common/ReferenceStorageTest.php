<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Common\ReferenceStorage
 * @covers \Tailors\PHPUnit\Common\ReferenceStorageTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ReferenceStorageTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCountOnFreshObject(): void
    {
        $storage = new ReferenceStorage();

        $this->assertSame(0, count($storage));
    }

    /**
     * @dataProvider provAddAndCount
     *
     * @psalm-param array $values
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAddAndCount(array $values, int $expect): void
    {
        $storage = new ReferenceStorage();

        /** @psalm-var mixed $value */
        foreach ($values as &$value) {
            $storage->add($value);
        }

        $this->assertSame($expect, count($storage));
    }

    /**
     * @dataProvider provAddRemoveContains
     *
     * @psalm-param array $values
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAddRemoveContains(array $values): void
    {
        $storage = new ReferenceStorage();

        /** @psalm-var mixed $value */
        foreach ($values as &$value) {
            $this->assertFalse($storage->contains($value));
            $storage->add($value);
            $this->assertTrue($storage->contains($value));
        }

        /** @psalm-var mixed $value */
        foreach ($values as &$value) {
            $storage->remove($value);
            $this->assertFalse($storage->contains($value));
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

        $this->assertTrue($storage->contains($var1));
        $this->assertTrue($storage->contains($var2));
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
        $this->assertTrue($storage->contains($arr1));
        $this->assertFalse($storage->contains($arr1['arr2']));
        $this->assertFalse($storage->contains($arr1['arr3']));
        $this->assertFalse($storage->contains($arr2));
        $this->assertTrue($storage->contains($arr2['arr1']));
        $this->assertFalse($storage->contains($arr2['arr3']));
        $this->assertFalse($storage->contains($arr3));
        $this->assertFalse($storage->contains($arr3['arr1']));
        $this->assertFalse($storage->contains($arr3['arr2']));

        $storage->add($arr2);
        $this->assertTrue($storage->contains($arr1));
        $this->assertTrue($storage->contains($arr1['arr2']));
        $this->assertFalse($storage->contains($arr1['arr3']));
        $this->assertTrue($storage->contains($arr2));
        $this->assertTrue($storage->contains($arr2['arr1']));
        $this->assertFalse($storage->contains($arr2['arr3']));
        $this->assertFalse($storage->contains($arr3));
        $this->assertFalse($storage->contains($arr3['arr1']));
        $this->assertFalse($storage->contains($arr3['arr2']));

        $storage->add($arr3);
        $this->assertTrue($storage->contains($arr1));
        $this->assertTrue($storage->contains($arr1['arr2']));
        $this->assertTrue($storage->contains($arr1['arr3']));
        $this->assertTrue($storage->contains($arr2));
        $this->assertTrue($storage->contains($arr2['arr1']));
        $this->assertTrue($storage->contains($arr2['arr3']));
        $this->assertTrue($storage->contains($arr3));
        $this->assertFalse($storage->contains($arr3['arr1']));
        $this->assertFalse($storage->contains($arr3['arr2']));
        $this->assertTrue($storage->contains($arr3['arr1']['arr2']));
        $this->assertTrue($storage->contains($arr3['arr1']['arr3']));
        $this->assertTrue($storage->contains($arr3['arr2']['arr1']));
        $this->assertTrue($storage->contains($arr3['arr2']['arr3']));
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
        $this->assertSame('var', $var);

        $this->assertTrue($storage->contains($var));
        $this->assertSame('var', $var);

        $storage->remove($var);
        $this->assertSame('var', $var);
    }

    /**
     * @psalm-return iterable<string, array{values: array, expect: int}>
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
