<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\AbstractArrayValues
 * @covers \Tailors\PHPUnit\Arrays\ExpectedArrayValues
 * @covers \Tailors\PHPUnit\Arrays\ArrayValuesTestCase
 * @covers \Tailors\PHPUnit\Arrays\AbstractArrayValuesTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedArrayValuesTest extends ArrayValuesTestCase
{
    public static function getArrayResultActual(): bool
    {
        return false;
    }

    public static function getValuesClass(): string
    {
        return ExpectedArrayValues::class;
    }

    public static function getArrayResultObject(array $ctorArgs): ResultInterface
    {
        return new ExpectedArrayValues(...$ctorArgs);
    }
}
// vim: syntax=php sw=4 ts=4 et:
