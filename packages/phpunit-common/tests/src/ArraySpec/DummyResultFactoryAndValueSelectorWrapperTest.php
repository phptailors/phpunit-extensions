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
 * @covers \Tailors\PHPUnit\ArraySpec\DummyResultFactoryAndValueSelectorWrapper
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from \Tailors\PHPUnit\Common\TypesInterface
 */
final class DummyResultFactoryAndValueSelectorWrapperTest extends TestCase
{
    /**
     * @psalm-param ArrayLike $array
     */
    public static function createDummyResultFactoryAndValueSelectorWrapper(
        iterable $array,
        ?ResultFactoryInterface $resultFactory = null,
        ?ValueSelectorInterface $valueSelector = null
    ): DummyResultFactoryAndValueSelectorWrapper {
        if (null === $resultFactory) {
            $resultFactory = new DummyResultFactory(false);
        }

        if (null === $valueSelector) {
            $valueSelector = new DummyValueSelector(false, false, '', '');
        }

        return new DummyResultFactoryAndValueSelectorWrapper($resultFactory, $valueSelector, $array);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsArrayObject(): void
    {
        $this->assertInstanceOf(\ArrayObject::class, self::createDummyResultFactoryAndValueSelectorWrapper([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultFactoryWrapperInterface(): void
    {
        $this->assertInstanceOf(ResultFactoryWrapperInterface::class, self::createDummyResultFactoryAndValueSelectorWrapper([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsValueSelectorWrapperInterface(): void
    {
        $this->assertInstanceOf(ValueSelectorWrapperInterface::class, self::createDummyResultFactoryAndValueSelectorWrapper([]));
    }

    /**
     * @psalm-return iterable<string, array{
     *      array: ArrayLike
     * }>
     */
    public static function provDummyResultFactoryAndValueSelectorWrapper(): iterable
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
     * @dataProvider provDummyResultFactoryAndValueSelectorWrapper
     *
     * @psalm-param ArrayLike $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyResultFactoryAndValueSelectorWrapper(iterable $array): void
    {
        $resultFactory = $this->createMock(ResultFactoryInterface::class);
        $valueSelector = $this->createMock(ValueSelectorInterface::class);

        $dummyWrapper = self::createDummyResultFactoryAndValueSelectorWrapper($array, $resultFactory, $valueSelector);

        $this->assertSame($resultFactory, $dummyWrapper->getResultFactory());
        $this->assertSame($valueSelector, $dummyWrapper->getValueSelector());

        $expect = is_array($array) ? $array : iterator_to_array($array);
        $this->assertSame($expect, iterator_to_array($dummyWrapper));
    }
}
