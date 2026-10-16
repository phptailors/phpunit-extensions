<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\Common\AbstractArrayObject;
use Tailors\PHPUnit\Common\AbstractArrayObjectTestCase;
use Tailors\PHPUnit\Common\ExportableNameInterface;
use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\TagInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Common\AbstractArrayObjectTestCase
 * @covers \Tailors\PHPUnit\Properties\AbstractObjectProperties
 * @covers \Tailors\PHPUnit\Properties\AbstractObjectPropertiesTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type CtorArgs from AbstractArrayObjectTestCase
 */
final class AbstractObjectPropertiesTest extends AbstractArrayObjectTestCase
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
        $tag = StaticRandomStrings::familyTag(__NAMESPACE__.'\ObjectProperties', '0f1d9297ad4259f9b8926b83329b4d82448592cd');
        self::assertSame($tag, self::getObject([])->tag());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testExportableName(): void
    {
        self::assertSame('ObjectProperties', AbstractObjectProperties::exportableName());
    }

    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return AbstractObjectProperties
     *
     * @codeCoverageIgnore
     */
    public static function getObject(array $ctorArgs): iterable
    {
        return new class(...$ctorArgs) extends AbstractObjectProperties {};
    }
}

// vim: syntax=php sw=4 ts=4 et:
