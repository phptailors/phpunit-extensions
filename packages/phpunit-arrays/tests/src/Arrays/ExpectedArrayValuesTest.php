<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(AbstractArrayValues::class)]
#[CoversClass(ArrayValuesTestCase::class)]
#[CoversClass(ExpectedArrayValues::class)]
#[Small]
final class ExpectedArrayValuesTest extends ArrayValuesTestCase
{
    public static function getValuesClass(): string
    {
        return ExpectedArrayValues::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
