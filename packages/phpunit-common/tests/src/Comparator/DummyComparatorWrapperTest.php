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
#[CoversClass(DummyComparatorWrapper::class)]
#[Small]
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
