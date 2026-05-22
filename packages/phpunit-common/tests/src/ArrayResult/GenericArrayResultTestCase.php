<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

use Tailors\PHPUnit\ArrayResult\ActualArrayResult;
use Tailors\PHPUnit\ArrayResult\AbstractGenericArrayResult;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type GenericArrayResultCtorArgs = list{0?: array|\Traversable<array-key,mixed>, 1?: non-empty-string}
 */
abstract class GenericArrayResultTestCase extends AbstractArrayResultTestCase
{
    /**
     * @psalm-return class-string<AbstractGenericArrayResult>
     */
    abstract public static function getArrayResultClass(): string;

    /**
     * @psalm-param GenericArrayResultCtorArgs $ctorArgs
     */
    final public static function getArrayResultObject(array $ctorArgs): AbstractGenericArrayResult
    {
        $class = static::getArrayResultClass();

        return new $class(...$ctorArgs);
    }

    final public static function getArrayResultFamilyName(): string
    {
        return __NAMESPACE__.'\GenericArrayResult';
    }

    final public static function getArrayResultActual(): bool
    {
        return ActualArrayResult::class === static::getArrayResultClass();
    }

    // @codeCoverageIgnoreStart
    /**
     * @psalm-return iterable<string, array{
     *      ctor: GenericArrayResultCtorArgs,
     *      expect: mixed
     * }>
     */
    public static function provGenericArrayResultTag(): iterable
    {
        // #1
        yield 'GenericArrayResultTestCase.php:'.__LINE__ => [
            'ctor'   => [['foo' => 'FOO'], 'TAGFOO'],
            'expect' => 'TAGFOO',
        ];
    }
    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provGenericArrayResultTag
     *
     * @param mixed $expect
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @psalm-param GenericArrayResultCtorArgs $ctor
     */
    final public function testGenericArrayResultTag(array $ctor, $expect): void
    {
        $object = self::getArrayResultObject($ctor);

        self::assertSame($expect, $object->tag());
    }
}
// vim: syntax=php sw=4 ts=4 et:
