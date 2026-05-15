<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\AbstractGenericValues
 * @covers \Tailors\PHPUnit\Values\AbstractValues
 * @covers \Tailors\PHPUnit\Values\AbstractValuesTestCase
 * @covers \Tailors\PHPUnit\Values\ExpectedValues
 * @covers \Tailors\PHPUnit\Values\GenericValuesTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedValuesTest extends GenericValuesTestCase
{
    public static function getValuesClass(): string
    {
        return ExpectedValues::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
