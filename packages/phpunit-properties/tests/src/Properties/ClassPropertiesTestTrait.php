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
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
trait ClassPropertiesTestTrait
{
    abstract public static function getValuesClass(): string;

    public static function getValuesFamilyName(): string
    {
        return __NAMESPACE__.'\ClassProperties';
    }

    public static function getValuesActual(): bool
    {
        return ActualClassProperties::class === self::getValuesClass();
    }
}
// vim: syntax=php sw=4 ts=4 et:
