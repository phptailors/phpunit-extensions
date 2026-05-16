<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Values\ArrayValueSelector;
use Tailors\PHPUnit\Values\ValueSelectorWrapperInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\AbstractArrayValues
 * @covers \Tailors\PHPUnit\Arrays\ArrayValuesTestCase
 * @covers \Tailors\PHPUnit\Arrays\ExpectedArrayValues
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedArrayValuesTest extends ArrayValuesTestCase
{
    public static function getValuesClass(): string
    {
        return ExpectedArrayValues::class;
    }

    public function testImplementsValueSelectorWrapperInterface(): void
    {
        $this->assertInstanceOf(ValueSelectorWrapperInterface::class, new ExpectedArrayValues());
    }

    public function testGetValueSelector(): void
    {
        $values = new ExpectedArrayValues();
        $selector = $values->getValueSelector();

        $this->assertInstanceOf(ArrayValueSelector::class, $selector);
        $this->assertSame($selector, $values->getValueSelector());
    }
}
// vim: syntax=php sw=4 ts=4 et:
