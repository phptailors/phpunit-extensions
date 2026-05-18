<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Recursive;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(DummyRecursiveVisitorStackItem::class)]
#[Small]
final class DummyRecursiveVisitorStackItemTest extends TestCase
{
    public function testDummyRecursiveVisitorStackItem(): void
    {
        $item = new DummyRecursiveVisitorStackItem(['node'], 'key');

        $this->assertSame(['node'], $item->node());
        $this->assertSame('key', $item->key());
    }
}
// vim: syntax=php sw=4 ts=4 et:
