<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultUnwrapper;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Recursive\RecursiveResultUnwrapperStackItem
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveResultUnwrapperStackItemTest extends TestCase
{
    public function testRecursiveResultUnwrapperStackItem(): void
    {
        $item = new RecursiveResultUnwrapperStackItem(['node'], 'key', ['result']);

        $this->assertSame(['node'], $item->node());
        $this->assertSame('key', $item->key());
        $this->assertSame(['result'], $item->result());
    }

    public function testSet(): void
    {
        $item = new RecursiveResultUnwrapperStackItem(['node'], 'key', ['result']);

        $this->assertSame(['result'], $item->result());

        $item->set('val');

        $this->assertSame(['result', 'key' => 'val'], $item->result());
    }
}
// vim: syntax=php sw=4 ts=4 et:
