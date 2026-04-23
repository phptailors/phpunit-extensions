<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\DummySelection
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class DummySelectionTest extends TestCase
{
    public function testDummySelection(): void
    {
        // Mostly for code coverage.
        $selector = $this->createStub(ValueSelectorInterface::class);

        $selection = new DummySelection($selector, false, ['a' => 'A']);
        $this->assertSame($selector, $selection->getSelector());
        $this->assertFalse($selection->actual());
        $this->assertSame(['a' => 'A'], $selection->getArrayCopy());

        $selection = new DummySelection($selector, true, ['b' => 'B']);
        $this->assertSame($selector, $selection->getSelector());
        $this->assertTrue($selection->actual());
        $this->assertSame(['b' => 'B'], $selection->getArrayCopy());
    }
}
// vim: syntax=php sw=4 ts=4 et:
