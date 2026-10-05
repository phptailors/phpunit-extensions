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
 * @covers \Tailors\PHPUnit\Properties\AbstractClassProperties
 * @covers \Tailors\PHPUnit\Properties\ClassPropertiesTestCase
 * @covers \Tailors\PHPUnit\Properties\ExpectedClassProperties
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 * @psalm-import-type CtorArgs from ClassPropertiesTestCase
 */
final class ExpectedClassPropertiesTest extends ClassPropertiesTestCase
{
    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return ArrayLike
     */
    final public static function getArrayResultObject(array $ctorArgs): iterable
    {
        return new ExpectedClassProperties(...$ctorArgs);
    }

    public static function getArrayResultActual(): bool
    {
        return false;
    }
}
// vim: syntax=php sw=4 ts=4 et:
