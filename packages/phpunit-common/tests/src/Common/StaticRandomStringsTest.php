<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Common\StaticRandomStrings
 * @covers \Tailors\PHPUnit\Common\StaticRandomStringsTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class StaticRandomStringsTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGet(): void
    {
        $str0 = StaticRandomStrings::get('str0');
        $str1 = StaticRandomStrings::get('str1');

        self::assertSame(StaticRandomStrings::STRINGLEN, strlen($str0));
        self::assertSame(StaticRandomStrings::STRINGLEN, strlen($str1));

        self::assertSame($str0, StaticRandomStrings::get('str0'));
        self::assertNotEquals($str0, $str1);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testClassTag(): void
    {
        $tag0 = StaticRandomStrings::classTag(new \Exception(''));
        $tag1 = StaticRandomStrings::classTag(self::class);

        self::assertSame(StaticRandomStrings::STRINGLEN + 1 + strlen(\Exception::class), strlen($tag0));
        self::assertSame(StaticRandomStrings::STRINGLEN + 1 + strlen(self::class), strlen($tag1));

        self::assertSame($tag0, StaticRandomStrings::classTag(\Exception::class));
        self::assertNotEquals($tag0, $tag1);

        self::assertStringStartsWith(\Exception::class.':', $tag0);
        self::assertStringStartsWith(self::class.':', $tag1);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFamilyTag(): void
    {
        $tag0 = StaticRandomStrings::familyTag('Smith');
        $tag1 = StaticRandomStrings::familyTag('Brown');

        self::assertSame(StaticRandomStrings::STRINGLEN + 1 + strlen('Smith'), strlen($tag0));
        self::assertSame(StaticRandomStrings::STRINGLEN + 1 + strlen('Brown'), strlen($tag1));

        self::assertSame($tag0, StaticRandomStrings::familyTag('Smith'));
        self::assertNotEquals($tag0, $tag1);

        self::assertStringStartsWith('Smith:', $tag0);
        self::assertStringStartsWith('Brown:', $tag1);
    }
}
// vim: syntax=php sw=4 ts=4 et:
