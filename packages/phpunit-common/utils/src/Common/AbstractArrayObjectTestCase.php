<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type CtorArgs = list{0?: ArrayLike}
 */
abstract class AbstractArrayObjectTestCase extends TestCase
{
    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return AbstractArrayObject
     */
    abstract public static function getObject(array $ctorArgs): iterable;

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      expect:   mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provArrayObject(): iterable
    {
        // #0
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [],
            'expect'   => [],
        ];

        // #1
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [[]],
            'expect'   => [],
        ];

        // #2
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [['foo' => 'FOO']],
            'expect'   => ['foo' => 'FOO'],
        ];

        // #3
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [new \ArrayObject(['foo' => 'FOO'])],
            'expect'   => ['foo' => 'FOO'],
        ];
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testExtendsArrayObject(): void
    {
        self::assertInstanceOf(\ArrayObject::class, static::getObject([]));
    }

    /**
     * @dataProvider provArrayObject
     *
     * @param mixed $expect
     *
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testArrayObject(array $ctorArgs, $expect): void
    {
        $object = static::getObject($ctorArgs);

        self::assertSame($expect, iterator_to_array($object));
        self::assertSame($expect, (array) $object);
    }
}
// vim: syntax=php sw=4 ts=4 et:
