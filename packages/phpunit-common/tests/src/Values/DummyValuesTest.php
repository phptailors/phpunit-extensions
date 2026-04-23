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
#[CoversClass(DummyValues::class)]
#[Small]
final class DummyValuesTest extends TestCase
{
    public function testDummyValues(): void
    {
        // Mostly for code coverage.
        $values = new DummyValues(false, ['a' => 'A']);
        $this->assertFalse($values->actual());
        $this->assertSame(['a' => 'A'], $values->getArrayCopy());

        $values = new DummyValues(true, ['b' => 'B']);
        $this->assertTrue($values->actual());
        $this->assertSame(['b' => 'B'], $values->getArrayCopy());
    }
}
// vim: syntax=php sw=4 ts=4 et:
