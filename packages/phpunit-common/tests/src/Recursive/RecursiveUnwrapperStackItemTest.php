<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Recursive;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Recursive\RecursiveUnwrapperStackItem
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveUnwrapperStackItemTest extends TestCase
{
    public function testRecursiveUnwrapperStackItem(): void
    {
        $item = new RecursiveUnwrapperStackItem(['node'], 'key', ['result']);

        $this->assertSame(['node'], $item->node());
        $this->assertSame('key', $item->key());
        $this->assertSame(['result'], $item->result());
    }

    public function testSet(): void
    {
        $item = new RecursiveUnwrapperStackItem(['node'], 'key', ['result']);

        $this->assertSame(['result'], $item->result());

        $item->set('val');

        $this->assertSame(['result', 'key' => 'val'], $item->result());
    }
}
// vim: syntax=php sw=4 ts=4 et:
