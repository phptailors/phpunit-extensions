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
 * @covers \Tailors\PHPUnit\ArraySpec\DummyValueSelectorWrapper
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from \Tailors\PHPUnit\Common\TypesInterface
 */
final class DummyValueSelectorWrapperTest extends TestCase
{
    /**
     * @psalm-param ArrayLike $array
     */
    public static function createDummyValueSelectorWrapper(
        iterable $array,
        ?ValueSelectorInterface $valueSelector = null
    ): DummyValueSelectorWrapper {
        if (null === $valueSelector) {
            $valueSelector = new DummyValueSelector(false, false, '', '');
        }

        return new DummyValueSelectorWrapper($valueSelector, $array);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsArrayObject(): void
    {
        $this->assertInstanceOf(\ArrayObject::class, self::createDummyValueSelectorWrapper([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsValueSelectorWrapperInterface(): void
    {
        $this->assertInstanceOf(ValueSelectorWrapperInterface::class, self::createDummyValueSelectorWrapper([]));
    }

    /**
     * @psalm-return iterable<string, array{
     *      array: ArrayLike
     * }>
     */
    public static function provDummyValueSelectorWrapper(): iterable
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
     * @dataProvider provDummyValueSelectorWrapper
     *
     * @psalm-param ArrayLike $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyValueSelectorWrapper(iterable $array): void
    {
        $valueSelector = $this->createMock(ValueSelectorInterface::class);

        $dummyValueSelectorWrapper = self::createDummyValueSelectorWrapper($array, $valueSelector);

        $this->assertSame($valueSelector, $dummyValueSelectorWrapper->getValueSelector());

        $expect = is_array($array) ? $array : iterator_to_array($array);
        $this->assertSame($expect, iterator_to_array($dummyValueSelectorWrapper));
    }
}
