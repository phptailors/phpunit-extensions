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
 *
 * @psalm-type CtorArgs = list{0?:array|\Traversable}
 */
trait AbstractValuesTestTrait
{
    abstract public static function getValuesClass(): string;

    abstract public static function getValuesFamilyName(): string;

    abstract public static function getValuesActual(): bool;

    abstract public static function assertInstanceOf(string $expected, $actual, string $message = ''): void;

    abstract public static function assertSame($expected, $actual, string $message = ''): void;

    abstract public static function assertTrue($actual, string $message = ''): void;

    /**
     * @psalm-param CtorArgs $ctor
     */
    public static function getValuesObject(array $ctor): ValuesInterface
    {
        $class = self::getValuesClass();

        return new $class(...$ctor);
    }

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
            'ctor'   => [],
            'expect' => [],
        ];

        // #1
        yield 'AbstractValuesTestTrait.php:'.__LINE__ => [
            'ctor'   => [[]],
            'expect' => [],
        ];

        // #2
        yield 'AbstractValuesTestTrait.php:'.__LINE__ => [
            'ctor'   => [['foo' => 'FOO']],
            'expect' => ['foo' => 'FOO'],
        ];

        // #3
        yield 'AbstractValuesTestTrait.php:'.__LINE__ => [
            'ctor'   => [new \ArrayObject(['foo' => 'FOO'])],
            'expect' => ['foo' => 'FOO'],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provAbstractValues
     *
     * @param mixed $expect
     *
     * @psalm-param list{0?:array|\Traversable} $ctor
     */
    public function testAbstractValues(array $ctor, $expect): void
    {
        $object = self::getValuesObject($ctor);

        self::assertSame($expect, iterator_to_array($object));
        self::assertSame($expect, (array) $object);
        self::assertSame(self::getValuesActual(), $object->actual());
    }

    // @codeCoverageIgnoreStart
    /**
     * @psalm-return iterable<string,array{ctor: CtorArgs}>
     */
    public static function provAbstractValuesTag(): iterable
    {
        yield 'AbstractValuesTestTrait.php:'.__LINE__ => ['ctor' => []];

        yield 'AbstractValuesTestTrait.php:'.__LINE__ => ['ctor' => [['foo' => 'FOO']]];
    }
    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provAbstractValuesTag
     *
     * @param mixed $expect
     *
     * @psalm-param CtorArgs $ctor
     */
    public function testAbstractValuesTag(array $ctor): void
    {
        $family = self::getValuesFamilyName();
        $familyHex = StaticRandomStrings::get($family);
        $familyTag = "{$family}:{$familyHex}";

        $object = self::getValuesObject($ctor);

        self::assertSame($familyTag, $object->tag());
    }

    public function testAbstractValuesCreateActualValues(): void
    {
        $object = self::getValuesObject([['o' => 'O']]);
        $actual = $object->createActualValues(['a' => 'A']);

        self::assertSame(['a' => 'A'], (array) $actual);
        self::assertTrue($actual->actual());
    }
}
// vim: syntax=php sw=4 ts=4 et:
