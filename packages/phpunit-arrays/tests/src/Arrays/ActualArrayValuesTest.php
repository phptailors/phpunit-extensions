<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Values\AbstractValuesTestTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\AbstractArrayValues
 * @covers \Tailors\PHPUnit\Arrays\ActualArrayValues
 * @covers \Tailors\PHPUnit\Arrays\ArrayValuesTestTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ActualArrayValuesTest extends TestCase
{
    use AbstractValuesTestTrait;
    use ArrayValuesTestTrait;

    // required by ValuesTestTrait
    public static function getValuesClass(): string
    {
        return ActualArrayValues::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
