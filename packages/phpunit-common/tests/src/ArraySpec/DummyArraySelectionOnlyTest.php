<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArraySpec;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ValueSelector\DummyValueSelector;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArraySpec\DummyArraySelectionOnly
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
final class DummyArraySelectionOnlyTest extends TestCase
{
    /**
     * @psalm-param ArrayLike $array
     */
    public static function createDummyArraySelectionOnly(
        iterable $array,
        ?ValueSelectorInterface $valueSelector = null
    ): DummyArraySelectionOnly {
        if (null === $valueSelector) {
            $valueSelector = new DummyValueSelector(false, false, '', '');
        }

        return new DummyArraySelectionOnly($valueSelector, $array);
    }

    public function testExtendsArrayObject(): void
    {
        $this->assertInstanceOf(\ArrayObject::class, self::createDummyArraySelectionOnly([]));
    }

    public function testImplementsValueSelectorWrapperInterface(): void
    {
        $this->assertInstanceOf(ValueSelectorWrapperInterface::class, self::createDummyArraySelectionOnly([]));
    }

    /**
     * @psalm-return iterable<string, array{
     *      array: ArrayLike
     * }>
     */
    public static function provDummyArraySelectionOnly(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'array' => new \ArrayObject(),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array' => new \ArrayObject(['a' => 'A']),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array' => ['a' => 'A'],
        ];
    }

    /**
     * @dataProvider provDummyArraySelectionOnly
     *
     * @psalm-param ArrayLike $array
     */
    public function testDummyArraySelectionOnly(iterable $array): void
    {
        $valueSelector = $this->createMock(ValueSelectorInterface::class);

        $dummyArraySelectionOnly = self::createDummyArraySelectionOnly($array, $valueSelector);

        $this->assertSame($valueSelector, $dummyArraySelectionOnly->getValueSelector());

        $expect = is_array($array) ? $array : iterator_to_array($array);
        $this->assertSame($expect, iterator_to_array($dummyArraySelectionOnly));
    }
}
