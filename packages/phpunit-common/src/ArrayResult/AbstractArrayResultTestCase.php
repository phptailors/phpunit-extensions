<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\TagInterface;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key,mixed>
 * @psalm-type AbstractArrayResultCtorArgs = array{0?: ArrayLike}
 */
abstract class AbstractArrayResultTestCase extends TestCase
{
    abstract public static function getArrayResultFamilyName(): string;

    abstract public static function getArrayResultActual(): bool;

    /**
     * @psalm-template CtorArgs of AbstractArrayResultCtorArgs
     *
     * @psalm-param CtorArgs $ctorArgs
     */
    abstract public static function getArrayResultObject(array $ctorArgs): ResultInterface;

    /**
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    final public function testImplementsResultInterface(): void
    {
        self::assertInstanceOf(ResultInterface::class, static::getArrayResultObject([]));
    }

    /**
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    final public function testExtendsArrayObject(): void
    {
        self::assertInstanceOf(\ArrayObject::class, static::getArrayResultObject([]));
    }

    // @codeCoverageIgnoreStart
    /**
     * @psalm-return iterable<string, array{
     *      ctor:   AbstractArrayResultCtorArgs,
     *      expect: mixed
     * }>
     */
    public static function provAbstractArrayResult(): iterable
    {
        // #0
        yield 'AbstractArrayResultTestCase.php:'.__LINE__ => [
            'ctor'   => [],
            'expect' => [],
        ];

        // #1
        yield 'AbstractArrayResultTestCase.php:'.__LINE__ => [
            'ctor'   => [[]],
            'expect' => [],
        ];

        // #2
        yield 'AbstractArrayResultTestCase.php:'.__LINE__ => [
            'ctor'   => [['foo' => 'FOO']],
            'expect' => ['foo' => 'FOO'],
        ];

        // #3
        yield 'AbstractArrayResultTestCase.php:'.__LINE__ => [
            'ctor'   => [new \ArrayObject(['foo' => 'FOO'])],
            'expect' => ['foo' => 'FOO'],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provAbstractArrayResult
     *
     * @param mixed $expect
     *
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @psalm-param AbstractArrayResultCtorArgs $ctor
     */
    final public function testAbstractArrayResult(array $ctor, $expect): void
    {
        $object = static::getArrayResultObject($ctor);

        self::assertInstanceOf(\Traversable::class, $object);
        self::assertSame($expect, iterator_to_array($object));
        self::assertSame($expect, (array) $object);
        self::assertSame(static::getArrayResultActual(), $object->actual());
    }

    // @codeCoverageIgnoreStart
    /**
     * @psalm-return iterable<string,array{ctor: AbstractArrayResultCtorArgs}>
     */
    public static function provAbstractArrayResultTag(): iterable
    {
        yield 'AbstractArrayResultTestCase.php:'.__LINE__ => ['ctor' => []];

        yield 'AbstractArrayResultTestCase.php:'.__LINE__ => ['ctor' => [['foo' => 'FOO']]];
    }
    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provAbstractArrayResultTag
     *
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @psalm-param AbstractArrayResultCtorArgs $ctor
     */
    final public function testAbstractArrayResultTag(array $ctor): void
    {
        $family = static::getArrayResultFamilyName();
        $familyHex = StaticRandomStrings::get($family);
        $familyTag = "{$family}:{$familyHex}";

        $object = static::getArrayResultObject($ctor);

        self::assertInstanceOf(TagInterface::class, $object);
        self::assertSame($familyTag, $object->tag());
    }
}
// vim: syntax=php sw=4 ts=4 et:
