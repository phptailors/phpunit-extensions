<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArrayResult\AbstractArrayResult
 * @covers \Tailors\PHPUnit\ArrayResult\AbstractArrayResultTestCase
 * @covers \Tailors\PHPUnit\ArrayResult\AbstractGenericArrayResult
 * @covers \Tailors\PHPUnit\ArrayResult\ExpectedArrayResult
 * @covers \Tailors\PHPUnit\ArrayResult\GenericArrayResultTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedArrayResultTest extends GenericArrayResultTestCase
{
    public static function getArrayResultClass(): string
    {
        return ExpectedArrayResult::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
