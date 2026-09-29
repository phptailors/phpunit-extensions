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

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArraySpec\DummyArraySpec
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
final class DummyArraySpecTest extends TestCase
{
    /**
     * @psalm-param ArrayLike $array
     */
    public static function createDummyArraySpec(
        iterable $array,
        ?ResultFactoryInterface $resultFactory = null
    ): DummyArraySpec {
        if (null === $resultFactory) {
            $resultFactory = new DummyResultFactory(false);
        }

        return new DummyArraySpec($resultFactory, $array);
    }

    public function testExtendsArrayObject(): void
    {
        $this->assertInstanceOf(\ArrayObject::class, self::createDummyArraySpec([]));
    }

    public function testImplementsResultFactoryWrapperInterface(): void
    {
        $this->assertInstanceOf(ResultFactoryWrapperInterface::class, self::createDummyArraySpec([]));
    }

    /**
     * @psalm-return iterable<string, array{
     *      array: ArrayLike
     * }>
     */
    public static function provDummyArraySpec(): iterable
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
     * @dataProvider provDummyArraySpec
     *
     * @psalm-param ArrayLike $array
     */
    public function testDummyArraySpec(iterable $array): void
    {
        $resultFactory = $this->createMock(ResultFactoryInterface::class);

        $dummyArraySpec = self::createDummyArraySpec($array, $resultFactory);

        $this->assertSame($resultFactory, $dummyArraySpec->getResultFactory());

        $expect = is_array($array) ? $array : iterator_to_array($array);
        $this->assertSame($expect, iterator_to_array($dummyArraySpec));
    }
}
