<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ArraySpec\AbstractArraySelectionSpec;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;
use Tailors\PHPUnit\ValueSelector\ArrayValueSelector;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\ArrayValuesSelection
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 */
final class ArrayValuesSelectionTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractArraySelectionSpec(): void
    {
        $this->assertInstanceOf(AbstractArraySelectionSpec::class, new ArrayValuesSelection([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultFactoryWrapperInterface(): void
    {
        $this->assertInstanceOf(ResultFactoryWrapperInterface::class, new ArrayValuesSelection([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementValueSelectorWrapperInterface(): void
    {
        $this->assertInstanceOf(ValueSelectorWrapperInterface::class, new ArrayValuesSelection([]));
    }

    /**
     * @psalm-return iterable<string, array{
     *      array: ArrayLike
     * }>
     */
    public static function provArrayValuesSelection(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'array' => ['a' => 'A'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array' => new \ArrayObject(['a' => 'A']),
        ];
    }

    /**
     * @dataProvider provArrayValuesSelection
     *
     * @psalm-param ArrayLike $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testArrayValuesSelection(iterable $array): void
    {
        $selection = new ArrayValuesSelection($array);

        $this->assertInstanceOf(ArrayValuesFactory::class, $selection->getResultFactory());
        $this->assertInstanceOf(ArrayValueSelector::class, $selection->getValueSelector());

        $expect = is_array($array) ? $array : iterator_to_array($array);
        $this->assertSame($expect, iterator_to_array($selection));
    }
}
// vim: syntax=php sw=4 ts=4 et:
