<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Comparator;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Comparator\DummyComparatorWrapper
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class DummyComparatorWrapperTest extends TestCase
{
    public function testDummyComparatorWrapper(): void
    {
        // Mostly for code coverage.
        $comparator = new DummyComparator(true, '');
        $wrapper = new DummyComparatorWrapper($comparator);
        $this->assertSame($comparator, $wrapper->getComparator());
    }
}
// vim: syntax=php sw=4 ts=4 et:
