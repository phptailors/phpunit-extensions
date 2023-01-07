<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\ActualValues
 * @covers \Tailors\PHPUnit\Values\ValuesTestTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ActualValuesTest extends TestCase
{
    use ValuesTestTrait;

    // required by ValuesTestTrait
    public static function getValuesClass(): string
    {
        return ActualValues::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
