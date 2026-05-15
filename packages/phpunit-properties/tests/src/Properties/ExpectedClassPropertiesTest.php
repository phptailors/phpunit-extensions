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
    public static function getValuesClass(): string
    {
        return ExpectedClassProperties::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
