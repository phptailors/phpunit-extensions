<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\Result\ResultInterface;


/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Properties\AbstractObjectProperties
 * @covers \Tailors\PHPUnit\Properties\ActualObjectProperties
 * @covers \Tailors\PHPUnit\Properties\ObjectPropertiesTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ActualObjectPropertiesTest extends ObjectPropertiesTestCase
{
    public static function getArrayResultObject(array $ctorArgs): ResultInterface
    {
        return new ActualObjectProperties(...$ctorArgs);
    }

    public static function getArrayResultActual(): bool
    {
        return true;
    }
}
// vim: syntax=php sw=4 ts=4 et:
