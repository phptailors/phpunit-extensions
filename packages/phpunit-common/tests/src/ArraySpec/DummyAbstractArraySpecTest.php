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

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArraySpec\AbstractArraySpec
 * @covers \Tailors\PHPUnit\ArraySpec\DummyAbstractArraySpec
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class DummyAbstractArraySpecTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractArraySpec(): void
    {
        $resultFactory = $this->createMock(ResultFactoryInterface::class);
        $this->assertInstanceOf(AbstractArraySpec::class, new DummyAbstractArraySpec($resultFactory, []));
    }

    /**
     * @psalm-return iterable<string, array{
     *      factory: ResultFactoryInterface,
     *      array:   ArrayLike
     * }>
     */
    public static function provDummyAbstractArraySpec(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'factory' => new DummyResultFactory(false),
            'array'   => ['a' => 'A'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'factory' => new DummyResultFactory(false),
            'array'   => new \ArrayObject(['a' => 'A']),
        ];
    }

    /**
     * @dataProvider provDummyAbstractArraySpec
     *
     * @psalm-template SupportedInput
     *
     * @psalm-param ResultFactoryInterface<SupportedInput> $factory
     * @psalm-param ArrayLike                              $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyAbstractArraySpec(ResultFactoryInterface $factory, iterable $array): void
    {
        $dummyArraySpec = new DummyAbstractArraySpec($factory, $array);

        $this->assertSame($factory, $dummyArraySpec->getResultFactory());

        $expect = is_array($array) ? $array : iterator_to_array($array);
        $this->assertSame($expect, iterator_to_array($dummyArraySpec));
    }
}
