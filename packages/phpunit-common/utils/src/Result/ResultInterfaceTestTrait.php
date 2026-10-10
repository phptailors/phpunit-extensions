<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Result;

use PHPUnit\Framework\TestCase;

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-require-extends TestCase
 *
 * @var TestCase $__phpactor__workaround__unused_import__TestCase
 */
trait ResultInterfaceTestTrait
{
    /**
     * @psalm-return iterable<string, array{
     *  object: ResultInterface,
     *  actual: mixed
     * }>
     */
    abstract public static function provActual(): iterable;

    /**
     * @psalm-return iterable<string, array{object: object}>
     */
    abstract public static function provImplementsResultInterface(): iterable;

    /**
     * @dataProvider provActual
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testActual(ResultInterface $object, $actual): void
    {
        self::assertSame($actual, $object->actual());
    }

    /**
     * @dataProvider provImplementsResultInterface
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsResultInterface(object $object): void
    {
        self::assertInstanceOf(ResultInterface::class, $object);
    }
}
