<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use PHPUnit\Framework\TestCase;

/**
 * @small
 * @covers \Tailors\PHPUnit\Properties\ExpectedPropertiesDecoratorTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedPropertiesDecoratorTraitTest extends TestCase
{
    // required by ExpectedPropertiesTestTrait
    public function createExpectedProperties(
        PropertySelectorInterface $selector,
        ...$args
    ): ExpectedPropertiesInterface {
        $properties = new ExpectedProperties($selector, ...$args);

        return $this->createDummyInstance($properties);
    }

    public static function createDummyInstance(ExpectedPropertiesInterface $wrapped): ExpectedPropertiesInterface
    {
        return new class($wrapped) implements ExpectedPropertiesInterface {
            use ExpectedPropertiesDecoratorTrait;

            private $wrapped;

            public function __construct(ExpectedPropertiesInterface $wrapped)
            {
                $this->wrapped = $wrapped;
            }

            public function getExpectedProperties(): ExpectedPropertiesInterface
            {
                return $this->wrapped;
            }
        };
    }

    //
    //
    // TESTS
    //
    //

    //
    // getIterator()
    //

    public function testGetIteratorInvokesAdapteeMethod(): void
    {
        $iterator = $this->createMock(\Traversable::class);
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects(self::once())
            ->method('getIterator')
            ->willReturn($iterator)
        ;
        $properties = $this->createDummyInstance($adaptee);
        self::assertSame($iterator, $properties->getIterator());
    }

    //
    // offsetExists()
    //

    public function testOffsetExistsInvokesAdapteeMethod(): void
    {
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects(self::once())
            ->method('offsetExists')
            ->with(123)
            ->willReturn(true)
        ;
        $properties = $this->createDummyInstance($adaptee);
        self::assertTrue($properties->offsetExists(123));
    }

    //
    // offsetGet()
    //

    public function testOffsetGetInvokesAdapteeMethod(): void
    {
        $value = new \StdClass();
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects(self::once())
            ->method('offsetGet')
            ->with(123)
            ->willReturn($value)
        ;
        $properties = $this->createDummyInstance($adaptee);
        self::assertSame($value, $properties->offsetGet(123));
    }

    //
    // offsetSet()
    //

    public function testOffsetSetInvokesAdapteeMethod(): void
    {
        $value = new \StdClass();
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects(self::once())
            ->method('offsetSet')
            ->with(123, $value)
        ;
        $properties = $this->createDummyInstance($adaptee);
        $properties->offsetSet(123, $value);
    }

    //
    // offsetUnset()
    //

    public function testOffsetUnsetInvokesAdapteeMethod(): void
    {
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects(self::once())
            ->method('offsetUnset')
            ->with(123)
        ;
        $properties = $this->createDummyInstance($adaptee);
        $properties->offsetUnset(123);
    }

    //
    // count()
    //

    public function testCountInvokesAdapteeMethod(): void
    {
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects(self::once())
            ->method('count')
            ->willReturn(123)
        ;
        $properties = $this->createDummyInstance($adaptee);
        self::assertSame(123, $properties->count());
    }

    //
    // getArrayCopy()
    //

    public function testGetArrayCopyInvokesAdapteeMethod(): void
    {
        $array = ['foo' => 'FOO'];
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects(self::once())
            ->method('getArrayCopy')
            ->willReturn($array)
        ;
        $properties = $this->createDummyInstance($adaptee);
        self::assertSame($array, $properties->getArrayCopy());
    }

    //
    // canUnwrapChild()
    //

    public function testCanUnwrapChildInvokesAdapteeMethod(): void
    {
        $child = $this->createMock(PropertiesInterface::class);
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects(self::once())
            ->method('canUnwrapChild')
            ->with($child)
            ->willReturn(true)
        ;
        $properties = $this->createDummyInstance($adaptee);
        self::assertTrue($properties->canUnwrapChild($child));
    }

    //
    // getPropertySelector()
    //

    public function testGetPropertySelectorInvokesAdapteeMethod(): void
    {
        $selector = $this->createMock(PropertySelectorInterface::class);
        $adaptee = $this->createMock(ExpectedPropertiesInterface::class);
        $adaptee->expects(self::once())
            ->method('getPropertySelector')
            ->willReturn($selector)
        ;
        $properties = $this->createDummyInstance($adaptee);
        self::assertSame($selector, $properties->getPropertySelector());
    }
}
// vim: syntax=php sw=4 ts=4 et:
