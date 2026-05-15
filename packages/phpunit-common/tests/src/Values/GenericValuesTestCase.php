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
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type GenericValuesCtorArgs = list{0?: array|\Traversable<array-key,mixed>, 1?: non-empty-string}
 */
abstract class GenericValuesTestCase extends AbstractValuesTestCase
{
    /**
     * @psalm-return class-string<AbstractGenericValues>
     */
    abstract public static function getValuesClass(): string;

    /**
     * @psalm-param GenericValuesCtorArgs $ctorArgs
     */
    final public static function getValuesObject(array $ctorArgs): ValuesInterface
    {
        $class = static::getValuesClass();

        return new $class(...$ctorArgs);
    }

    final public static function getValuesFamilyName(): string
    {
        return __NAMESPACE__.'\GenericValues';
    }

    final public static function getValuesActual(): bool
    {
        return ActualValues::class === static::getValuesClass();
    }

    // @codeCoverageIgnoreStart
    /**
     * @psalm-return iterable<string, array{
     *      ctor: GenericValuesCtorArgs,
     *      expect: mixed
     * }>
     */
    public static function provGenericValuesTag(): iterable
    {
        // #1
        yield 'GenericValuesTestCase.php:'.__LINE__ => [
            'ctor'   => [['foo' => 'FOO'], 'TAGFOO'],
            'expect' => 'TAGFOO',
        ];
    }
    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provGenericValuesTag
     *
     * @param mixed $expect
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @psalm-param GenericValuesCtorArgs $ctor
     */
    final public function testGenericValuesTag(array $ctor, $expect): void
    {
        $object = self::getValuesObject($ctor);

        self::assertSame($expect, $object->tag());
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    final public function testGenericValuesCreateActualValues(): void
    {
        $object = self::getValuesObject([[], 'TAGFOO']);
        $actual = $object->createActualValues(['foo' => 'FOO']);

        self::assertSame(['foo' => 'FOO'], (array) $actual);
        self::assertSame('TAGFOO', $actual->tag());
    }
}
// vim: syntax=php sw=4 ts=4 et:
