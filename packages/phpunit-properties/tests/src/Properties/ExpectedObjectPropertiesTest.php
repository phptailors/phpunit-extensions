<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\Common\TypesInterface;

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
 *
 * @psalm-import-type ArrayLike from TypesInterface
 * @psalm-import-type CtorArgs from ObjectPropertiesTestCase
 */
final class ExpectedObjectPropertiesTest extends ObjectPropertiesTestCase
{
    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return ArrayLike
     */
    public static function getArrayResultObject(array $ctorArgs): iterable
    {
        return new ExpectedObjectProperties(...$ctorArgs);
    }

    public static function getArrayResultActual(): bool
    {
        return false;
    }
}
// vim: syntax=php sw=4 ts=4 et:
