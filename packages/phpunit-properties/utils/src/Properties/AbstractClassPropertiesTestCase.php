<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\Common\AbstractArrayObjectTestCase;
use Tailors\PHPUnit\Common\ExportableNameInterface;
use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\TagInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type CtorArgs from AbstractArrayObjectTestCase
 */
abstract class AbstractClassPropertiesTestCase extends AbstractArrayObjectTestCase
{
    /**
     * @psalm-pure
     *
     * @psalm-return class-string<AbstractClassProperties>
     */
    abstract public static function getClass(): string;

    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return AbstractClassProperties
     */
    public static function getObject(array $ctorArgs): iterable
    {
        $class = static::getClass();

        return new $class(...$ctorArgs);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsTagInterface(): void
    {
        $object = static::getObject([]);
        self::assertInstanceOf(TagInterface::class, $object);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsExportableNameInterface(): void
    {
        $object = static::getObject([]);
        self::assertInstanceOf(ExportableNameInterface::class, $object);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testTag(): void
    {
        $object = static::getObject([]);
        $tag = StaticRandomStrings::familyTag(__NAMESPACE__.'\ClassProperties', '204ae0e60189915fbbb65c711dff84ddb77710c6');
        self::assertSame($tag, $object->tag());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testExportableName(): void
    {
        $class = static::getClass();
        self::assertSame('ClassProperties', $class::exportableName());
    }
}
// vim: syntax=php sw=4 ts=4 et:
