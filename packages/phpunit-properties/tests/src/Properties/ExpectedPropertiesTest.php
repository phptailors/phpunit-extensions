<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Tailors\PHPUnit\Properties;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @small
 * @covers \Tailors\PHPUnit\Properties\ExpectedProperties
 * @covers \Tailors\PHPUnit\Properties\ExpectedPropertiesTestTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedPropertiesTest extends TestCase
{
    use ExpectedPropertiesTestTrait;
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;

    // required by ExpectedPropertiesTestTrait
    public function createExpectedProperties(
        PropertySelectorInterface $selector,
        ...$args
    ): ExpectedPropertiesInterface {
        return new ExpectedProperties($selector, ...$args);
    }

    //
    //
    // TESTS
    //
    //

    public function testImplementsExpectedPropertiesInterface(): void
    {
        self::assertImplementsInterface(ExpectedPropertiesInterface::class, ExpectedProperties::class);
    }

    public function testExtendsArrayObject(): void
    {
        self::assertExtendsClass(\ArrayObject::class, ExpectedProperties::class);
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
        $selector = $this->createMock(PropertySelectorInterface::class);

        $properties = new ExpectedProperties($selector, ...$args);

        self::assertSame($selector, $properties->getPropertySelector());
        self::assertSame($expect, $properties->getArrayCopy());
        self::assertSame($expect, (array) $properties);
    }
}
// vim: syntax=php sw=4 ts=4 et:
