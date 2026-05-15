<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\Values\AbstractValuesTestCase;
use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type AbstractValuesCtorArgs from AbstractValuesTestCase as ObjectPropertiesCtorArgs
 */
abstract class ObjectPropertiesTestCase extends AbstractValuesTestCase
{
    /**
     * @return class-string<AbstractObjectProperties>
     */
    abstract public static function getValuesClass(): string;

    /**
     * @psalm-param ObjectPropertiesCtorArgs $ctorArgs
     */
    final public static function getValuesObject(array $ctorArgs): ValuesInterface
    {
        $class = static::getValuesClass();

        return new $class(...$ctorArgs);
    }

    public static function getValuesFamilyName(): string
    {
        return __NAMESPACE__.'\ObjectProperties';
    }

    public static function getValuesActual(): bool
    {
        return ActualObjectProperties::class === static::getValuesClass();
    }
}
// vim: syntax=php sw=4 ts=4 et:
