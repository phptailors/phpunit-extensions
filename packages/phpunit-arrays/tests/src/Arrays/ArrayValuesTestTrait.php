<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
trait ArrayValuesTestTrait
{
    abstract public static function getValuesClass(): string;

    //
    //
    // TESTS
    //
    //

    public function testImplementsValuesInterface(): void
    {
        $class = self::getValuesClass();
        self::assertInstanceOf(ValuesInterface::class, new $class());
    }

    public function testExtendsArrayObject(): void
    {
        $class = self::getValuesClass();
        self::assertInstanceOf(\ArrayObject::class, new $class());
    }

    // @codeCoverageIgnoreStart
    public static function provValues(): iterable
    {
        // #0
        yield 'ArrayValuesTestTrait.php:'.__LINE__ => [
            'args'   => [],
            'expect' => [],
        ];

        // #1
        yield 'ArrayValuesTestTrait.php:'.__LINE__ => [
            'args'   => [[]],
            'expect' => [],
        ];

        // #2
        yield 'ArrayValuesTestTrait.php:'.__LINE__ => [
            'args'   => [['foo' => 'FOO']],
            'expect' => ['foo' => 'FOO'],
        ];

        // #3
        yield 'ArrayValuesTestTrait.php:'.__LINE__ => [
            'args'   => [new \ArrayObject(['foo' => 'FOO'])],
            'expect' => ['foo' => 'FOO'],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provValues
     *
     * @param mixed $expect
     *
     * @psalm-param list{0?:array|\Traversable} $args
     */
    public function testValues(array $args, $expect): void
    {
        $class = self::getValuesClass();
        $object = new $class(...$args);

        self::assertSame($expect, iterator_to_array($object));
        self::assertSame($expect, (array) $object);
        self::assertSame(ActualArrayValues::class === $class, $object->actual());
    }

    // @codeCoverageIgnoreStart
    public static function provTag(): iterable
    {
        $family = __NAMESPACE__.'\ArrayValues';
        $familyHex = StaticRandomStrings::get($family);
        $familyTag = "{$family}:{$familyHex}";

        // #0
        yield 'ArrayValuesTestTrait.php:'.__LINE__ => [
            'args'   => [],
            'expect' => $familyTag,
        ];

        // #1
        yield 'ArrayValuesTestTrait.php:'.__LINE__ => [
            'args'   => [['foo' => 'FOO']],
            'expect' => $familyTag,
        ];
    }
    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provTag
     *
     * @param mixed $expect
     *
     * @psalm-param list{0?:array|\Traversable,1?:null|non-empty-string} $args
     */
    public function testTag(array $args, $expect): void
    {
        $class = self::getValuesClass();
        $object = new $class(...$args);

        $this->assertSame($expect, $object->tag());
    }

    public function testCreateActualArrayValues(): void
    {
        $class = self::getValuesClass();
        $object = new $class([]);
        $actual = $object->createActualValues(['foo' => 'FOO']);

        $this->assertSame(['foo' => 'FOO'], (array) $actual);
        $this->assertSame($object->tag(), $actual->tag());
    }
}
// vim: syntax=php sw=4 ts=4 et:
