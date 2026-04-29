<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[Small]
#[CoversClass(DummyValueSelectorWrapper::class)]
final class DummyValueSelectorWrapperTest extends TestCase
{
    public function testDummyValueSelectorWrapper(): void
    {
        // Mostly for code coverage.
        $valueSelector = $this->createStub(ValueSelectorInterface::class);
        $wrapper = new DummyValueSelectorWrapper($valueSelector);
        $this->assertSame($valueSelector, $wrapper->getValueSelector());
    }
}
// vim: syntax=php sw=4 ts=4 et:
