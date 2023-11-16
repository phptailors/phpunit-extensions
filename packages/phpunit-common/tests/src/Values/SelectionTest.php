<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\Selection
 *
 * @internal This class is not covered by the backward compatibility promise
 *
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

    public static function provConstruct(): array
    {
        $arrayObject = new \ArrayObject(['foo', 'bar']);

        return [
            'SelectionTest.php:'.__LINE__ => [
                'args'   => fn (ValueSelectorInterface $selector) => [$selector],
                'expect' => [
                    'array' => [],
                ],
            ],

            'SelectionTest.php:'.__LINE__ => [
                'args'   => fn (ValueSelectorInterface $selector) => [$selector, ['foo', 'bar']],
                'expect' => [
                    'array' => ['foo', 'bar'],
                ],
            ],

            'SelectionTest.php:'.__LINE__ => [
                'args'   => fn (ValueSelectorInterface $selector) => [$selector, $arrayObject],
                'expect' => [
                    'array' => ['foo', 'bar'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provConstruct
     *
     * @param \Closure(ValueSelectorInterface):array $args
     */
    public function testConstruct(\Closure $args, array $expect): void
    {
        $selector = $this->createMock(ValueSelectorInterface::class);

        $selection = new Selection(...$args($selector));
        self::assertSame($selector, $selection->getSelector());
        self::assertSame($expect['array'], $selection->getArrayCopy());
    }
}
// vim: syntax=php sw=4 ts=4 et:
