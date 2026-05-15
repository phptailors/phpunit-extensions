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
 * @covers \Tailors\PHPUnit\Properties\AbstractObjectProperties
 * @covers \Tailors\PHPUnit\Properties\ExpectedObjectProperties
 * @covers \Tailors\PHPUnit\Properties\ObjectPropertiesTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedObjectPropertiesTest extends ObjectPropertiesTestCase
{
    public static function getValuesClass(): string
    {
        return ExpectedObjectProperties::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
