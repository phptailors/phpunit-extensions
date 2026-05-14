<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\Common\StaticRandomStrings;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
trait AbstractValuesTestTrait
{
    abstract public static function getValuesClass(): string;

    abstract public static function getValuesFamilyName(): string;

    abstract public static function getValuesActual(): bool;

    abstract public static function assertInstanceOf(string $expected, $actual, string $message = ''): void;

    abstract public static function assertSame($expected, $actual, string $message = ''): void;

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
    public static function provAbstractValues(): iterable
    {
        // #0
        yield 'AbstractValuesTestTrait.php:'.__LINE__ => [
            'args'   => [],
            'expect' => [],
        ];

        // #1
        yield 'AbstractValuesTestTrait.php:'.__LINE__ => [
            'args'   => [[]],
            'expect' => [],
        ];

        // #2
        yield 'AbstractValuesTestTrait.php:'.__LINE__ => [
            'args'   => [['foo' => 'FOO']],
            'expect' => ['foo' => 'FOO'],
        ];

        // #3
        yield 'AbstractValuesTestTrait.php:'.__LINE__ => [
            'args'   => [new \ArrayObject(['foo' => 'FOO'])],
            'expect' => ['foo' => 'FOO'],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provAbstractValues
     *
     * @param mixed $expect
     *
     * @psalm-param list{0?:array|\Traversable} $args
     */
    public function testAbstractValues(array $args, $expect): void
    {
        $class = self::getValuesClass();
        $object = new $class(...$args);

        self::assertSame($expect, iterator_to_array($object));
        self::assertSame($expect, (array) $object);
        self::assertSame(self::getValuesActual(), $object->actual());
    }

    // @codeCoverageIgnoreStart
    public static function provAbstractValuesTag(): iterable
    {
        $family = self::getValuesFamilyName();
        $familyHex = StaticRandomStrings::get($family);
        $familyTag = "{$family}:{$familyHex}";

        // #0
        yield 'AbstractValuesTestTrait.php:'.__LINE__ => [
            'args'   => [],
            'expect' => $familyTag,
        ];

        // #1
        yield 'AbstractValuesTestTrait.php:'.__LINE__ => [
            'args'   => [['foo' => 'FOO']],
            'expect' => $familyTag,
        ];
    }
    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provAbstractValuesTag
     *
     * @param mixed $expect
     *
     * @psalm-param list{0?:array|\Traversable,1?:null|non-empty-string} $args
     */
    public function testAbstractValuesTag(array $args, $expect): void
    {
        $class = self::getValuesClass();
        $object = new $class(...$args);

        self::assertSame($expect, $object->tag());
    }
}
// vim: syntax=php sw=4 ts=4 et:
