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
trait GenericValuesTestTrait
{
    abstract public static function getValuesClass(): string;

    abstract public static function assertInstanceOf(string $expected, $actual, string $message = ''): void;

    abstract public static function assertSame($expected, $actual, string $message = ''): void;

    public static function getValuesFamilyName(): string
    {
        return __NAMESPACE__.'\GenericValues';
    }

    public static function getValuesActual(): bool
    {
        return ActualValues::class === self::getValuesClass();
    }

    // @codeCoverageIgnoreStart
    public static function provGenericValuesTag(): iterable
    {
        // #1
        yield 'GenericValuesTestTrait.php:'.__LINE__ => [
            'args'   => [['foo' => 'FOO'], 'TAGFOO'],
            'expect' => 'TAGFOO',
        ];
    }
    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provGenericValuesTag
     *
     * @psalm-param list{0?:array|\Traversable,1?:null|non-empty-string} $args
     */
    public function testGenericValuesTag(array $args, mixed $expect): void
    {
        $class = self::getValuesClass();
        $object = new $class(...$args);

        $this->assertSame($expect, $object->tag());
    }

    public function testGenericValuesCreateActualValues(): void
    {
        $class = self::getValuesClass();
        $object = new $class([], 'TAGFOO');
        $actual = $object->createActualValues(['foo' => 'FOO']);

        $this->assertSame(['foo' => 'FOO'], (array) $actual);
        $this->assertSame('TAGFOO', $actual->tag());
    }
}
// vim: syntax=php sw=4 ts=4 et:
