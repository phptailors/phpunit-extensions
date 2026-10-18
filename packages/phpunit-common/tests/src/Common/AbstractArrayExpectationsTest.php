<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ResultFactory\DummyResultFactory;
use Tailors\PHPUnit\ResultFactory\ResultFactoryInterface;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 */
#[CoversClass(AbstractArrayExpectations::class)]
#[CoversClass(AbstractArrayExpectationsTest::class)]
#[Small]
final class AbstractArrayExpectationsTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractArrayObject(): void
    {
        $arrayExpectations = $this->createStub(AbstractArrayExpectations::class);
        self::assertInstanceOf(AbstractArrayObject::class, $arrayExpectations);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultFactoryWrapperInterface(): void
    {
        $arrayExpectations = $this->createStub(AbstractArrayExpectations::class);
        self::assertInstanceOf(ResultFactoryWrapperInterface::class, $arrayExpectations);
    }

    /**
     * @psalm-return iterable<string, array{
     *      resultFactory: ResultFactoryInterface,
     *      array:   ArrayLike
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provAbstractArrayExpectations(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'resultFactory' => new DummyResultFactory(false),
            'array'         => ['a' => 'A'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'resultFactory' => new DummyResultFactory(false),
            'array'         => new \ArrayObject(['a' => 'A']),
        ];
    }

    /**
     * @psalm-template SupportedInput
     *
     * @psalm-param ResultFactoryInterface<SupportedInput> $resultFactory
     * @psalm-param ArrayLike                              $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provAbstractArrayExpectations')]
    public function testAbstractArrayExpectations(ResultFactoryInterface $resultFactory, iterable $array): void
    {
        $arrayExpectations = self::getTestableObject($resultFactory, $array);

        self::assertSame($resultFactory, $arrayExpectations->getResultFactory());

        $expect = is_array($array) ? $array : iterator_to_array($array);
        self::assertSame($expect, iterator_to_array($arrayExpectations));
    }

    /**
     * @psalm-template SupportedInput
     *
     * @psalm-param ResultFactoryInterface<SupportedInput> $resultFactory
     * @psalm-param ArrayLike                              $array
     */
    private static function getTestableObject(ResultFactoryInterface $resultFactory, iterable $array): AbstractArrayExpectations
    {
        return new
            /**
             * @psalm-template SupportedInput
             *
             * @template-extends AbstractArrayExpectations<SupportedInput>
             */
            class($resultFactory, $array) extends AbstractArrayExpectations {
                /**
                 * @psalm-param ResultFactoryInterface<SupportedInput> $resultFactory
                 * @psalm-param ArrayLike                              $array
                 */
                public function __construct(ResultFactoryInterface $resultFactory, iterable $array)
                {
                    parent::__construct($resultFactory, $array);
                }
            };
    }
}
