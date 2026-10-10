<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\Result\ResultInterface;
use Tailors\PHPUnit\Result\ResultInterfaceTestTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type CtorArgs = list{0?: ArrayLike}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
abstract class AbstractArrayResultTestCase extends TestCase
{
    use ResultInterfaceTestTrait;

    /**
     * @psalm-pure
     */
    abstract public static function getActual(): bool;

    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return AbstractArrayResult
     */
    abstract public static function getObject(array $ctorArgs): iterable;

    // @codeCoverageIgnoreStart
    /**
     * @psalm-return iterable<string, array{
     *  object: ResultInterface,
     *  actual: mixed
     * }>
     */
    public static function provActual(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'object' => static::getObject([]),
            'actual' => static::getActual(),
        ];
    }

    /**
     * @psalm-return iterable<string, array{object: object}>
     */
    public static function provImplementsResultInterface(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'object' => static::getObject([]),
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      expect:   mixed
     * }>
     */
    public static function provArray(): iterable
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

    // @codeCoverageIgnoreEnd

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testExtendsArrayObject(): void
    {
        self::assertInstanceOf(\ArrayObject::class, static::getObject([]));
    }

    /**
     * @dataProvider provArray
     *
     * @param mixed $expect
     *
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testArray(array $ctorArgs, $expect): void
    {
        $object = static::getObject($ctorArgs);

        self::assertSame($expect, iterator_to_array($object));
        self::assertSame($expect, (array) $object);
        self::assertSame(static::getActual(), $object->actual());
    }
}
// vim: syntax=php sw=4 ts=4 et:
