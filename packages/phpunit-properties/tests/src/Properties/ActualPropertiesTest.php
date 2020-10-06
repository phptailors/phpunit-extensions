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
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @small
 * @covers \Tailors\PHPUnit\Properties\ActualProperties
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class ActualPropertiesTest extends TestCase
{
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;

    //
    //
    // TESTS
    //
    //

    public function testImplementsActualPropertiesInterface(): void
    {
        self::assertImplementsInterface(ActualPropertiesInterface::class, ActualProperties::class);
    }

    public function testExtendsArrayObject(): void
    {
        self::assertExtendsClass(\ArrayObject::class, ActualProperties::class);
    }

    //
    // __construct()
    //

    public static function provConstruct(): array
    {
        return [
            // #0
            [
                'args'   => [],
                'expect' => [],
            ],

            // #1
            [
                'args'   => [[]],
                'expect' => [],
            ],

            // #2
            [
                'args'   => [['foo' => 'FOO']],
                'expect' => ['foo' => 'FOO'],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $properties = new ActualProperties(...$args);

        self::assertSame($expect, $properties->getArrayCopy());
        self::assertSame($expect, (array) $properties);
    }

    //
    // canUnwrapChild()
    //

    public function provCanUnwrapChild(): array
    {
        $selector = $this->createMock(PropertySelectorInterface::class);

        return [
            // #0
            [
                'parent' => new ActualProperties(),
                'child'  => new ExpectedProperties($selector),
                'expect' => false,
            ],

            // #1
            [
                'parent' => new ActualProperties(),
                'child'  => new ActualProperties(),
                'expect' => true,
            ],
        ];
    }

    /**
     * @dataProvider provCanUnwrapChild
     */
    public function testCanUnwrapChild(PropertiesInterface $parent, PropertiesInterface $child, bool $expect): void
    {
        self::assertSame($expect, $parent->canUnwrapChild($child));
    }
}
// vim: syntax=php sw=4 ts=4 et:
