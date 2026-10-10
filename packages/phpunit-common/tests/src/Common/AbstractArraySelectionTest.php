<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Comparator\DummyComparator;
use Tailors\PHPUnit\Predicate\ComparatorAwareComplementInterface;
use Tailors\PHPUnit\ResultFactory\DummyResultFactory;
use Tailors\PHPUnit\ResultFactory\ResultFactoryInterface;
use Tailors\PHPUnit\ValueSelector\DummyValueSelector;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Common\AbstractArraySelection
 * @covers \Tailors\PHPUnit\Common\AbstractArraySelectionTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 */
final class AbstractArraySelectionTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractArrayExpectations(): void
    {
        $arraySelection = $this->createStub(AbstractArraySelection::class);
        $this->assertInstanceOf(AbstractArrayExpectations::class, $arraySelection);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsValueSelectorWrapperInterface(): void
    {
        $arraySelection = $this->createStub(AbstractArraySelection::class);
        $this->assertInstanceOf(ValueSelectorWrapperInterface::class, $arraySelection);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsComparatorAwareComplementInterface(): void
    {
        $arraySelection = $this->createStub(AbstractArraySelection::class);
        $this->assertInstanceOf(ComparatorAwareComplementInterface::class, $arraySelection);
    }

    /**
     * @psalm-return iterable<string, array{
     *      resultFactory: ResultFactoryInterface,
     *      valueSelector: ValueSelectorInterface,
     *      array:         ArrayLike
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provAbstractArraySelection(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'resultFactory' => new DummyResultFactory(false),
            'valueSelector' => new DummyValueSelector(false, false, '', ''),
            'array'         => ['a' => 'A'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'resultFactory' => new DummyResultFactory(false),
            'valueSelector' => new DummyValueSelector(false, false, '', ''),
            'array'         => new \ArrayObject(['a' => 'A']),
        ];
    }

    /**
     * @dataProvider provAbstractArraySelection
     *
     * @psalm-template SupportedInput
     * @psalm-template SupportedSubject
     *
     * @psalm-param ResultFactoryInterface<SupportedInput>   $resultFactory
     * @psalm-param ValueSelectorInterface<SupportedSubject> $valueSelector
     * @psalm-param ArrayLike                                $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAbstractArraySelection(
        ResultFactoryInterface $resultFactory,
        ValueSelectorInterface $valueSelector,
        iterable $array
    ): void {
        $arraySelection = self::getTestableObject($resultFactory, $valueSelector, $array);

        $this->assertSame($resultFactory, $arraySelection->getResultFactory());
        $this->assertSame($valueSelector, $arraySelection->getValueSelector());

        $expect = is_array($array) ? $array : iterator_to_array($array);
        $this->assertSame($expect, iterator_to_array($arraySelection));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testComplement(): void
    {
        $arraySelection = self::getTestableObject(
            new DummyResultFactory(false),
            new DummyValueSelector(false, false, 'rainbow', 'colors'),
            []
        );

        $comparator = new DummyComparator(false, 'similar to');

        $this->assertSame('rainbow with colors similar to the specified ones', $arraySelection->complement($comparator));
    }

    /**
     * @psalm-template SupportedInput
     * @psalm-template SupportedSubject
     *
     * @psalm-param ResultFactoryInterface<SupportedInput>   $resultFactory
     * @psalm-param ValueSelectorInterface<SupportedSubject> $valueSelector
     * @psalm-param ArrayLike                                $array
     */
    private static function getTestableObject(
        ResultFactoryInterface $resultFactory,
        ValueSelectorInterface $valueSelector,
        iterable $array
    ): AbstractArraySelection {
        return new
            /**
             * @psalm-template SupportedInput
             * @psalm-template SupportedSubject
             *
             * @template-extends AbstractArraySelection<SupportedInput, SupportedSubject>
             */
            class($resultFactory, $valueSelector, $array) extends AbstractArraySelection {
                /**
                 * @psalm-param ResultFactoryInterface<SupportedInput>   $resultFactory
                 * @psalm-param ValueSelectorInterface<SupportedSubject> $valueSelector
                 * @psalm-param ArrayLike                                $array
                 */
                public function __construct(ResultFactoryInterface $resultFactory, ValueSelectorInterface $valueSelector, iterable $array)
                {
                    parent::__construct($resultFactory, $valueSelector, $array);
                }
            };
    }
}
