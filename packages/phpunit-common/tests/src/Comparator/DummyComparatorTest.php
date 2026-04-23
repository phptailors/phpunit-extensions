<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Comparator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(DummyComparator::class)]
#[Small]
final class DummyComparatorTest extends TestCase
{
    public function testDummyComparator(): void
    {
        // Mostly for code coverage.
        $comparator = new DummyComparator(false, 'foo');
        $this->assertFalse($comparator->compare('a', 'b'));
        $this->assertSame('foo', $comparator->adjective());

        $comparator = new DummyComparator(true, 'bar');
        $this->assertTrue($comparator->compare('c', 'c'));
        $this->assertSame('bar', $comparator->adjective());
    }
}
// vim: syntax=php sw=4 ts=4 et:
