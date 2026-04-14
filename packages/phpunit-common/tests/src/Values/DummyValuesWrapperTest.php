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
 * @covers \Tailors\PHPUnit\Values\DummyValuesWrapper
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class DummyValuesWrapperTest extends TestCase
{
    public function testDummyValuesWrapper(): void
    {
        // Mostly for code coverage.
        $values = new ActualValues();
        $wrapper = new DummyValuesWrapper($values);
        $this->assertSame($values, $wrapper->getValues());
    }
}
// vim: syntax=php sw=4 ts=4 et:
