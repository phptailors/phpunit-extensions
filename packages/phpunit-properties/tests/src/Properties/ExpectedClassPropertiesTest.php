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
 * @covers \Tailors\PHPUnit\Properties\AbstractClassProperties
 * @covers \Tailors\PHPUnit\Properties\ClassPropertiesTestCase
 * @covers \Tailors\PHPUnit\Properties\ExpectedClassProperties
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedClassPropertiesTest extends ClassPropertiesTestCase
{
    /**
     * @psalm-param ClassPropertiesCtorArgs $ctorArgs
     */
    final public static function getArrayResultObject(array $ctorArgs): ExpectedClassProperties
    {
        return new ExpectedClassProperties(...$ctorArgs);
    }

    public static function getArrayResultActual(): bool
    {
        return false;
    }
}
// vim: syntax=php sw=4 ts=4 et:
