<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\AbstractArraySelection;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;
use Tailors\PHPUnit\ValueSelector\ClassPropertySelector;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Properties\ClassPropertiesSelection
 * @covers \Tailors\PHPUnit\Properties\ClassPropertiesSelectionTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class ClassPropertiesSelectionTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractArraySelectionSpec(): void
    {
        $this->assertInstanceOf(AbstractArraySelection::class, new ClassPropertiesSelection([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultFactoryWrapperInterface(): void
    {
        $this->assertInstanceOf(ResultFactoryWrapperInterface::class, new ClassPropertiesSelection([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementValueSelectorWrapperInterface(): void
    {
        $this->assertInstanceOf(ValueSelectorWrapperInterface::class, new ClassPropertiesSelection([]));
    }

    /**
     * @psalm-return iterable<string, array{
     *      array: ArrayLike
     * }>
     */
    public static function provClassPropertiesSelection(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'array' => ['a' => 'A'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array' => new \ArrayObject(['a' => 'A']),
        ];
    }

    /**
     * @dataProvider provClassPropertiesSelection
     *
     * @psalm-param ArrayLike $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testClassPropertiesSelection(iterable $array): void
    {
        $selection = new ClassPropertiesSelection($array);

        $this->assertInstanceOf(ClassPropertiesFactory::class, $selection->getResultFactory());
        $this->assertInstanceOf(ClassPropertySelector::class, $selection->getValueSelector());

        $expect = is_array($array) ? $array : iterator_to_array($array);
        $this->assertSame($expect, iterator_to_array($selection));
    }
}
// vim: syntax=php sw=4 ts=4 et:
