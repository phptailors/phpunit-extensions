<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(StaticRandomStrings::class)]
#[Small]
final class StaticRandomStringsTest extends TestCase
{
    public function testGet(): void
    {
        $str0 = StaticRandomStrings::get('str0');
        $str1 = StaticRandomStrings::get('str1');

        $this->assertSame(StaticRandomStrings::STRINGLEN, strlen($str0));
        $this->assertSame(StaticRandomStrings::STRINGLEN, strlen($str1));

        $this->assertSame($str0, StaticRandomStrings::get('str0'));
        $this->assertNotEquals($str0, $str1);
    }
}
// vim: syntax=php sw=4 ts=4 et:
