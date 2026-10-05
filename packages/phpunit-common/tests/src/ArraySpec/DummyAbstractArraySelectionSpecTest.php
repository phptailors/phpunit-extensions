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
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\ResultFactory\DummyResultFactory;
use Tailors\PHPUnit\ResultFactory\ResultFactoryInterface;
use Tailors\PHPUnit\ValueSelector\DummyValueSelector;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArraySpec\AbstractArraySelectionSpec
 * @covers \Tailors\PHPUnit\ArraySpec\DummyAbstractArraySelectionSpec
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 */
final class DummyAbstractArraySelectionSpecTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractArraySelectionSpec(): void
    {
        $resultFactory = $this->createMock(ResultFactoryInterface::class);
        $valueSelector = $this->createMock(ValueSelectorInterface::class);
        $this->assertInstanceOf(AbstractArraySelectionSpec::class, new DummyAbstractArraySelectionSpec($resultFactory, $valueSelector, []));
    }

    /**
     * @psalm-return iterable<string, array{
     *      factory:  ResultFactoryInterface,
     *      selector: ValueSelectorInterface,
     *      array:    ArrayLike
     * }>
     */
    public static function provDummyAbstractArraySelectionSpec(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'factory'  => new DummyResultFactory(false),
            'selector' => new DummyValueSelector(false, false, '', ''),
            'array'    => ['a' => 'A'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'factory'  => new DummyResultFactory(false),
            'selector' => new DummyValueSelector(false, false, '', ''),
            'array'    => new \ArrayObject(['a' => 'A']),
        ];
    }

    /**
     * @dataProvider provDummyAbstractArraySelectionSpec
     *
     * @psalm-template SupportedInput
     * @psalm-template SupportedSubject
     *
     * @psalm-param ResultFactoryInterface<SupportedInput>   $factory
     * @psalm-param ValueSelectorInterface<SupportedSubject> $selector
     * @psalm-param ArrayLike                                $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyAbstractArraySelectionSpec(
        ResultFactoryInterface $factory,
        ValueSelectorInterface $selector,
        iterable $array
    ): void {
        $dummyArraySelectionSpec = new DummyAbstractArraySelectionSpec($factory, $selector, $array);

        $this->assertSame($factory, $dummyArraySelectionSpec->getResultFactory());
        $this->assertSame($selector, $dummyArraySelectionSpec->getValueSelector());

        $expect = is_array($array) ? $array : iterator_to_array($array);
        $this->assertSame($expect, iterator_to_array($dummyArraySelectionSpec));
    }
}
