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
        self::assertSame(ActualValues::class === $class, $object->actual());
    }
}
// vim: syntax=php sw=4 ts=4 et:
