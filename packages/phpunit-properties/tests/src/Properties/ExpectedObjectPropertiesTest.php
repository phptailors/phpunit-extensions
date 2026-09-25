<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Properties\AbstractObjectProperties
 * @covers \Tailors\PHPUnit\Properties\ExpectedObjectProperties
 * @covers \Tailors\PHPUnit\Properties\ObjectPropertiesTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedObjectPropertiesTest extends ObjectPropertiesTestCase
{
    public static function getArrayResultObject(array $ctorArgs): ExpectedObjectProperties
    {
        return new ExpectedObjectProperties(...$ctorArgs);
    }

    public static function getArrayResultActual(): bool
    {
        return false;
    }
//
//    public function testImplementsValueSelectorWrapperInterface(): void
//    {
//        $this->assertInstanceOf(ValueSelectorWrapperInterface::class, new ExpectedObjectProperties());
//    }
//
//    public function testGetValueSelector(): void
//    {
//        $values = new ExpectedObjectProperties();
//        $selector = $values->getValueSelector();
//
//        $this->assertInstanceOf(ObjectPropertySelector::class, $selector);
//        $this->assertSame($selector, $values->getValueSelector());
//    }
}
// vim: syntax=php sw=4 ts=4 et:
