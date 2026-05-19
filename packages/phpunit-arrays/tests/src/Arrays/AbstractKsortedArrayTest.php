<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Common\AbstractArrayObject;
use Tailors\PHPUnit\Common\AbstractArrayObjectTestCase;
use Tailors\PHPUnit\Common\ExportableNameInterface;
use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\TagInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\AbstractKsortedArray
 * @covers \Tailors\PHPUnit\Arrays\AbstractKsortedArrayTest
 * @covers \Tailors\PHPUnit\Common\AbstractArrayObjectTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type CtorArgs from AbstractArrayObjectTestCase
 */
final class AbstractKsortedArrayTest extends AbstractArrayObjectTestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testExtendsAbstractArrayObject(): void
    {
        self::assertInstanceOf(AbstractArrayObject::class, self::getObject([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsTagInterface(): void
    {
        self::assertInstanceOf(TagInterface::class, self::getObject([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsExportableNameInterface(): void
    {
        self::assertInstanceOf(ExportableNameInterface::class, self::getObject([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testTag(): void
    {
        $tag = StaticRandomStrings::familyTag(__NAMESPACE__.'\KsortedArray', 'e81092c9084f0813af075edd32d0beb2f3c774f3');
        self::assertSame($tag, self::getObject([])->tag());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testExportableName(): void
    {
        self::assertSame('KsortedArray', AbstractKsortedArray::exportableName());
    }

    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return AbstractKsortedArray
     */
    public static function getObject(array $ctorArgs): iterable
    {
        return new class(...$ctorArgs) extends AbstractKsortedArray {};
    }
}

// vim: syntax=php sw=4 ts=4 et:
