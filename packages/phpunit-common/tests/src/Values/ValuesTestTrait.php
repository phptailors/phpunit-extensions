<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
trait ValuesTestTrait
{
    abstract public static function getValuesClass(): string;
    abstract public static function getValuesActual(): bool;

    /**
     * @param mixed $expected
     * @param mixed $actual
     */
    abstract public static function assertSame($expected, $actual, string $message = ''): void;

    /**
     * @param mixed $actual
     */
    abstract public static function assertInstanceOf(string $expected, $actual, string $message = ''): void;

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
    public static function provValues(): array
    {
        return [
            // #0
            [
                'args'   => [],
                'expect' => [],
            ],

            // #1
            [
                'args'   => [[]],
                'expect' => [],
            ],

            // #2
            [
                'args'   => [['foo' => 'FOO']],
                'expect' => ['foo' => 'FOO'],
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provValues
     */
    public function testValues(array $args, array $expect): void
    {
        $class = self::getValuesClass();
        $object = new $class(...$args);

        self::assertSame($expect, $object->getArrayCopy());
        self::assertSame($expect, (array) $object);
    }

    public function testActual(): void
    {
        $class = self::getValuesClass();
        $object = new $class();

        self::assertSame(self::getValuesActual(), $object->actual());
    }
}
// vim: syntax=php sw=4 ts=4 et:
