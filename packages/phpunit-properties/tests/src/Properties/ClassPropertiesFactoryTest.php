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
use Tailors\PHPUnit\ResultFactory\AbstractArrayResultFactory;
use Tailors\PHPUnit\ResultFactory\ResultFactoryInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Properties\ClassPropertiesFactory
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ClassPropertiesFactoryTest extends TestCase
{
    public function testExtendsAbstractArrayResultFactory(): void
    {
        $this->assertInstanceOf(AbstractArrayResultFactory::class, new ClassPropertiesFactory());
    }

    public function testImplementsResultFactoryInterface(): void
    {
        $this->assertInstanceOf(ResultFactoryInterface::class, new ClassPropertiesFactory());
    }

    /**
     * @psalm-return iterable<string, array{
     *      actual: bool
     *      array: ArrayLike,
     *      expect: array{
     *          class: mixed,
     *          array: mixed
     *      }
     * }>
     */
    public static function provGetResult(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'actual' => false,
            'array'  => ['a' => 'A'],
            'expect' => [
                'class' => ExpectedClassProperties::class,
                'array' => ['a' => 'A'],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'actual' => true,
            'array'  => ['a' => 'A'],
            'expect' => [
                'class' => ActualClassProperties::class,
                'array' => ['a' => 'A'],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'actual' => false,
            'array'  => new \ArrayObject(['a' => 'A']),
            'expect' => [
                'class' => ExpectedClassProperties::class,
                'array' => ['a' => 'A'],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'actual' => true,
            'array'  => new \ArrayObject(['a' => 'A']),
            'expect' => [
                'class' => ActualClassProperties::class,
                'array' => ['a' => 'A'],
            ],
        ];
    }

    /**
     * @dataProvider provGetResult
     *
     * @psalm-param ArrayLike           $array
     * @psalm-param array{class: mixed} $expect
     */
    public function testGetResult(bool $actual, iterable $array, array $expect): void
    {
        $factory = new ClassPropertiesFactory();

        $result = $factory->getResult($actual, $array);

        $this->assertInstanceOf($expect['class'], $result);
        $this->assertIsIterable($result);
        $this->assertSame($expect['array'], iterator_to_array($result));
    }
}
// vim: syntax=php sw=4 ts=4 et:
