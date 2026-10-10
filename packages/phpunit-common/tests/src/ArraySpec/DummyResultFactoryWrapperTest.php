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
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArraySpec\DummyResultFactoryWrapper
 * @covers \Tailors\PHPUnit\ArraySpec\DummyResultFactoryWrapperTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class DummyResultFactoryWrapperTest extends TestCase
{
    /**
     * @psalm-param ArrayLike $array
     */
    public static function createDummyResultFactoryWrapper(
        iterable $array,
        ?ResultFactoryInterface $resultFactory = null
    ): DummyResultFactoryWrapper {
        if (null === $resultFactory) {
            $resultFactory = new DummyResultFactory(false);
        }

        return new DummyResultFactoryWrapper($resultFactory, $array);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsArrayObject(): void
    {
        $this->assertInstanceOf(\ArrayObject::class, self::createDummyResultFactoryWrapper([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultFactoryWrapperInterface(): void
    {
        $this->assertInstanceOf(ResultFactoryWrapperInterface::class, self::createDummyResultFactoryWrapper([]));
    }

    /**
     * @psalm-return iterable<string, array{
     *      array: ArrayLike
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provDummyResultFactoryWrapper(): iterable
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
     * @dataProvider provDummyResultFactoryWrapper
     *
     * @psalm-param ArrayLike $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyResultFactoryWrapper(iterable $array): void
    {
        $resultFactory = $this->createMock(ResultFactoryInterface::class);

        $dummyResultFactoryWrapper = self::createDummyResultFactoryWrapper($array, $resultFactory);

        $this->assertSame($resultFactory, $dummyResultFactoryWrapper->getResultFactory());

        $expect = is_array($array) ? $array : iterator_to_array($array);
        $this->assertSame($expect, iterator_to_array($dummyResultFactoryWrapper));
    }
}
