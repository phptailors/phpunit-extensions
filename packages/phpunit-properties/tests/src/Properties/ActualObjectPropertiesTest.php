<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArrayResult\AbstractArrayResultTestCase
 * @covers \Tailors\PHPUnit\Properties\AbstractObjectProperties
 * @covers \Tailors\PHPUnit\Properties\AbstractObjectPropertiesTestCase
 * @covers \Tailors\PHPUnit\Properties\ActualObjectProperties
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type CtorArgs from AbstractObjectPropertiesTestCase
 */
final class ActualObjectPropertiesTest extends AbstractObjectPropertiesTestCase
{
    /**
     * @psalm-pure
     */
    public static function getActual(): bool
    {
        return true;
    }

    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return ActualObjectProperties
     */
    public static function getObject(array $ctorArgs): iterable
    {
        return new ActualObjectProperties(...$ctorArgs);
    }

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<ActualObjectProperties>
     */
    public static function getClass(): string
    {
        return ActualObjectProperties::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
