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
use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(ReferenceStorage::class)]
final class ReferenceStorageTest extends TestCase
{
    public function testCountOnFreshObject(): void
    {
        $storage = new ReferenceStorage();

        $this->assertSame(0, count($storage));
    }

    /**
     * @param array $values
     */
    #[DataProvider('provAddAndCount')]
    public function testAddAndCount(array $values, int $expect): void
    {
        $storage = new ReferenceStorage();

        foreach ($values as &$value) {
            $storage->add($value);
        }

        $this->assertSame($expect, count($storage));
    }

    /**
     * @param array $values
     */
    #[DataProvider('provAddRemoveContains')]
    public function testAddRemoveContains(array $values): void
    {
        $storage = new ReferenceStorage();

        foreach ($values as &$value) {
            $this->assertFalse($storage->contains($value));
            $storage->add($value);
            $this->assertTrue($storage->contains($value));
        }

        foreach ($values as &$value) {
            $storage->remove($value);
            $this->assertFalse($storage->contains($value));
        }
    }

    public function testWithAliasedVariables(): void
    {
        $storage = new ReferenceStorage();

        $var1 = 'var1';
        $var2 = &$var1;

        $storage->add($var1);

        $this->assertTrue($storage->contains($var1));
        $this->assertTrue($storage->contains($var2));
    }

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
     * @return iterable<string, array{values: array, expect: int}>
     */
    public static function provAddAndCount(): iterable
    {
        yield 'ReferenceStorageTest.php:'.__LINE__ => [
            'values' => [], 'expect' => 0,
        ];

        yield 'ReferenceStorageTest.php:'.__LINE__ => [
            'values' => ['a'], 'expect' => 1,
        ];

        yield 'ReferenceStorageTest.php:'.__LINE__ => [
            'values' => [new \stdClass()], 'expect' => 1,
        ];

        yield 'ReferenceStorageTest.php:'.__LINE__ => [
            'values' => ['a', new \stdClass()], 'expect' => 2,
        ];

        yield 'ReferenceStorageTest.php:'.__LINE__ => [
            'values' => ['a', new \stdClass(), ['x' => 'X', 'y' => ['z' => 'Z']]], 'expect' => 3,
        ];
    }

    /**
     * @return iterable<string, array{values: array}>
     */
    public static function provAddRemoveContains(): iterable
    {
        yield 'ReferenceStorageTest.php:'.__LINE__ => [
            'values' => ['a'],
        ];

        yield 'ReferenceStorageTest.php:'.__LINE__ => [
            'values' => [new \stdClass()],
        ];

        yield 'ReferenceStorageTest.php:'.__LINE__ => [
            'values' => ['a', new \stdClass(), ['x' => 'X', 'y' => ['z' => 'Z']]],
        ];
    }
}
// vim: syntax=php sw=4 ts=4 et:
