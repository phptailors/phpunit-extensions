<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(ExpectedValues::class)]
#[CoversClass(ValuesTestTrait::class)]
final class ExpectedValuesTest extends TestCase
{
    use ValuesTestTrait;

    // required by ValuesTestTrait
    public static function getValuesClass(): string
    {
        return ExpectedValues::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
