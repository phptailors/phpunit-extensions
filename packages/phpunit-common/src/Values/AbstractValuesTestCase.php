<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\StaticRandomStrings;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type AbstractValuesCtorArgs = array{0?: array|\Traversable<array-key,mixed>}
 */
abstract class AbstractValuesTestCase extends TestCase
{
    abstract public static function getValuesFamilyName(): string;

    abstract public static function getValuesActual(): bool;

    /**
     * @psalm-template CtorArgs of AbstractValuesCtorArgs
     *
     * @psalm-param CtorArgs $ctorArgs
     */
    abstract public static function getValuesObject(array $ctorArgs): ValuesInterface;

    /**
     * @throws Exception
     * @throws ExpectationFailedException
     */
    final public function testImplementsValuesInterface(): void
    {
        self::assertInstanceOf(ValuesInterface::class, static::getValuesObject([]));
    }

    /**
     * @throws Exception
     * @throws ExpectationFailedException
     */
    final public function testExtendsArrayObject(): void
    {
        self::assertInstanceOf(\ArrayObject::class, static::getValuesObject([]));
    }

    // @codeCoverageIgnoreStart
    /**
     * @psalm-return iterable<string, array{
     *      ctor:   AbstractValuesCtorArgs,
     *      expect: mixed
     * }>
     */
    public static function provAbstractValues(): iterable
    {
        // #0
        yield 'AbstractValuesTestCase.php:'.__LINE__ => [
            'ctor'   => [],
            'expect' => [],
        ];

        // #1
        yield 'AbstractValuesTestCase.php:'.__LINE__ => [
            'ctor'   => [[]],
            'expect' => [],
        ];

        // #2
        yield 'AbstractValuesTestCase.php:'.__LINE__ => [
            'ctor'   => [['foo' => 'FOO']],
            'expect' => ['foo' => 'FOO'],
        ];

        // #3
        yield 'AbstractValuesTestCase.php:'.__LINE__ => [
            'ctor'   => [new \ArrayObject(['foo' => 'FOO'])],
            'expect' => ['foo' => 'FOO'],
        ];
    }

    // @codeCoverageIgnoreEnd
    /**
     * @throws ExpectationFailedException
     *
     * @psalm-param AbstractValuesCtorArgs $ctor
     */
    #[DataProvider('provAbstractValues')]
    final public function testAbstractValues(array $ctor, mixed $expect): void
    {
        $object = static::getValuesObject($ctor);

        self::assertSame($expect, iterator_to_array($object));
        self::assertSame($expect, (array) $object);
        self::assertSame(static::getValuesActual(), $object->actual());
    }

    // @codeCoverageIgnoreStart
    /**
     * @psalm-return iterable<string,array{ctor: AbstractValuesCtorArgs}>
     */
    public static function provAbstractValuesTag(): iterable
    {
        yield 'AbstractValuesTestCase.php:'.__LINE__ => ['ctor' => []];

        yield 'AbstractValuesTestCase.php:'.__LINE__ => ['ctor' => [['foo' => 'FOO']]];
    }

    // @codeCoverageIgnoreEnd
    /**
     * @throws ExpectationFailedException
     *
     * @psalm-param AbstractValuesCtorArgs $ctor
     */
    #[DataProvider('provAbstractValuesTag')]
    final public function testAbstractValuesTag(array $ctor): void
    {
        $family = static::getValuesFamilyName();
        $familyHex = StaticRandomStrings::get($family);
        $familyTag = "{$family}:{$familyHex}";

        $object = static::getValuesObject($ctor);

        self::assertSame($familyTag, $object->tag());
    }

    /**
     * @throws ExpectationFailedException
     */
    final public function testAbstractValuesCreateActualValues(): void
    {
        $object = static::getValuesObject([['o' => 'O']]);
        $actual = $object->createActualValues(['a' => 'A']);

        self::assertSame(['a' => 'A'], (array) $actual);
        self::assertTrue($actual->actual());
    }
}
// vim: syntax=php sw=4 ts=4 et:
