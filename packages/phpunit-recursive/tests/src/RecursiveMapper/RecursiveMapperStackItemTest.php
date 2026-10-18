<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveMapper;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(RecursiveMapperStackItem::class)]
#[CoversClass(RecursiveMapperStackItemTest::class)]
#[Small]
final class RecursiveMapperStackItemTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testRecursiveMapperStackItem(): void
    {
        $item = new RecursiveMapperStackItem(['node'], 'key', ['result']);

        self::assertSame(['node'], $item->node());
        self::assertSame('key', $item->key());
        self::assertSame(['result'], $item->result());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSet(): void
    {
        $item = new RecursiveMapperStackItem(['node'], 'key', ['result']);

        self::assertSame(['result'], $item->result());

        $item->set('val');

        self::assertSame(['result', 'key' => 'val'], $item->result());
    }
}
// vim: syntax=php sw=4 ts=4 et:
