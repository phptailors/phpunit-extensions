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
 * @covers \Tailors\PHPUnit\Properties\AbstractClassProperties
 * @covers \Tailors\PHPUnit\Properties\AbstractClassPropertiesTestCase
 * @covers \Tailors\PHPUnit\Properties\ExpectedClassProperties
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type CtorArgs from AbstractClassPropertiesTestCase
 */
final class ExpectedClassPropertiesTest extends AbstractClassPropertiesTestCase
{
    /**
     * @psalm-pure
     */
    public static function getActual(): bool
    {
        return false;
    }

    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return ExpectedClassProperties
     */
    public static function getObject(array $ctorArgs): iterable
    {
        return new ExpectedClassProperties(...$ctorArgs);
    }

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<ExpectedClassProperties>
     */
    public static function getClass(): string
    {
        return ExpectedClassProperties::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
