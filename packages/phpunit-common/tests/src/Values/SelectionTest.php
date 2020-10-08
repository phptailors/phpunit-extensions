<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\TestCase;

/**
 * @small
 * @covers \Tailors\PHPUnit\Values\Selection
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class SelectionTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function testExtendsExpectedValues(): void
    {
        $selector = $this->createMock(ValueSelectorInterface::class);
        self::assertInstanceOf(ExpectedValues::class, new Selection($selector));
    }

    public function testImplementsSelectionInterface(): void
    {
        $selector = $this->createMock(ValueSelectorInterface::class);
        self::assertInstanceOf(SelectionInterface::class, new Selection($selector));
    }

    public function provConstruct(): array
    {
        $selector = $this->createMock(ValueSelectorInterface::class);

        return [
            'SelectionTest.php:'.__LINE__ => [
                'args'   => [$selector],
                'expect' => [
                    'selector' => $selector,
                    'array'    => [],
                ],
            ],

            'SelectionTest.php:'.__LINE__ => [
                'args'   => [$selector, ['foo', 'bar']],
                'expect' => [
                    'selector' => $selector,
                    'array'    => ['foo', 'bar'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     */
    public function testConstruct(array $args, array $expect): void
    {
        $selection = new Selection(...$args);
        self::assertSame($expect['selector'], $selection->getSelector());
        self::assertSame($expect['array'], $selection->getArrayCopy());
    }
}
// vim: syntax=php sw=4 ts=4 et:
