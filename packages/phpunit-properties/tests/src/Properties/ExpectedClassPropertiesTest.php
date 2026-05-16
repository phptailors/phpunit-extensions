<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use Tailors\PHPUnit\Values\ClassPropertySelector;
use Tailors\PHPUnit\Values\ValueSelectorWrapperInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(AbstractClassProperties::class)]
#[CoversClass(ClassPropertiesTestCase::class)]
#[CoversClass(ExpectedClassProperties::class)]
#[Small]
final class ExpectedClassPropertiesTest extends ClassPropertiesTestCase
{
    public static function getValuesClass(): string
    {
        return ExpectedClassProperties::class;
    }

    public function testImplementsValueSelectorWrapperInterface(): void
    {
        $this->assertInstanceOf(ValueSelectorWrapperInterface::class, new ExpectedClassProperties());
    }

    public function testGetValueSelector(): void
    {
        $values = new ExpectedClassProperties();
        $selector = $values->getValueSelector();

        $this->assertInstanceOf(ClassPropertySelector::class, $selector);
        $this->assertSame($selector, $values->getValueSelector());
    }
}
// vim: syntax=php sw=4 ts=4 et:
