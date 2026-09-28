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
use Tailors\PHPUnit\ResultFactory\DummyResultFactory;
use Tailors\PHPUnit\ResultFactory\ResultFactoryInterface;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;
use Tailors\PHPUnit\ValueSelector\DummyValueSelector;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArraySpec\DummyArraySelection
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike   = iterable<array-key, mixed>
 */
final class DummyArraySelectionTest extends TestCase
{
    /**
     * @psalm-param ArrayLike $array
     */
    public static function createDummyArraySelection(
        iterable $array,
        ?ResultFactoryInterface $resultFactory = null,
        ?ValueSelectorInterface $valueSelector = null
    ): DummyArraySelection {
        if (null === $resultFactory) {
            $resultFactory = new DummyResultFactory(false);
        }

        if (null === $valueSelector) {
            $valueSelector = new DummyValueSelector(false, false, '', '');
        }

        return new DummyArraySelection($resultFactory, $valueSelector, $array);
    }

    public function testExtendsArrayObject(): void
    {
        $this->assertInstanceOf(\ArrayObject::class, self::createDummyArraySelection([]));
    }

    public function testImplementsResultFactoryWrapperInterface(): void
    {
        $this->assertInstanceOf(ResultFactoryWrapperInterface::class, self::createDummyArraySelection([]));
    }

    public function testImplementsValueSelectorWrapperInterface(): void
    {
        $this->assertInstanceOf(ValueSelectorWrapperInterface::class, self::createDummyArraySelection([]));
    }

    /**
     * @psalm-return iterable<string, array{
     *      array: ArrayLike
     * }>
     */
    public static function provDummyArraySelection(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'array' => new \ArrayObject(),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array' => new \ArrayObject(['a' => 'A']),
        ];

        yield basename(__file__).':'.__line__ => [
            'array' => [],
        ];

        yield basename(__file__).':'.__line__ => [
            'array' => ['a' => 'A'],
        ];
    }


    /**
     * @dataProvider provDummyArraySelection
     *
     * @psalm-param ArrayLike $array
     */
    public function testDummyArraySelection(iterable $array): void
    {
        $resultFactory = $this->createMock(ResultFactoryInterface::class);
        $valueSelector = $this->createMock(ValueSelectorInterface::class);

        $arraySelection = self::createDummyArraySelection($array, $resultFactory, $valueSelector);

        $this->assertSame($resultFactory, $arraySelection->getResultFactory());
        $this->assertSame($valueSelector, $arraySelection->getValueSelector());

        $expect = is_array($array) ? $array : iterator_to_array($array);
        $this->assertSame($expect, iterator_to_array($arraySelection));
    }
}
