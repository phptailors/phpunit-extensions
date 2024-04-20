<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(ExpectedValuesSelection::class)]
final class ExpectedValuesSelectionTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function testExtendsExpectedValues(): void
    {
        $selector = $this->createMock(ValueSelectorInterface::class);
        self::assertInstanceOf(ExpectedValues::class, new ExpectedValuesSelection($selector));
    }

    public function testImplementsSelectionInterface(): void
    {
        $selector = $this->createMock(ValueSelectorInterface::class);
        self::assertInstanceOf(SelectionInterface::class, new ExpectedValuesSelection($selector));
    }

    public static function provConstruct(): array
    {
        $arrayObject = new \ArrayObject(['foo', 'bar']);

        return [
            'ExpectedValuesSelectionTest.php:'.__LINE__ => [
                'args'   => fn (ValueSelectorInterface $selector) => [$selector],
                'expect' => [
                    'array' => [],
                ],
            ],

            'ExpectedValuesSelectionTest.php:'.__LINE__ => [
                'args'   => fn (ValueSelectorInterface $selector) => [$selector, ['foo', 'bar']],
                'expect' => [
                    'array' => ['foo', 'bar'],
                ],
            ],

            'ExpectedValuesSelectionTest.php:'.__LINE__ => [
                'args'   => fn (ValueSelectorInterface $selector) => [$selector, $arrayObject],
                'expect' => [
                    'array' => ['foo', 'bar'],
                ],
            ],
        ];
    }

    /**
     * @param \Closure(ValueSelectorInterface):array $args
     */
    #[DataProvider('provConstruct')]
    public function testConstruct(\Closure $args, array $expect): void
    {
        $selector = $this->createMock(ValueSelectorInterface::class);

        $selection = new ExpectedValuesSelection(...$args($selector));
        self::assertSame($selector, $selection->getSelector());
        self::assertSame($expect['array'], $selection->getArrayCopy());
    }
}
// vim: syntax=php sw=4 ts=4 et:
