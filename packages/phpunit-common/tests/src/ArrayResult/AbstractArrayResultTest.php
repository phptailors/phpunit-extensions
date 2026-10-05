<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\TagInterface;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArrayResult\AbstractArrayResult
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from \Tailors\PHPUnit\Common\TypesInterface
 */
final class AbstractArrayResultTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsArrayObject(): void
    {
        $abstractArrayResult = $this->createStub(AbstractArrayResult::class);
        $this->assertInstanceOf(\ArrayObject::class, $abstractArrayResult);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultInterface(): void
    {
        $abstractArrayResult = $this->createStub(AbstractArrayResult::class);
        $this->assertInstanceOf(ResultInterface::class, $abstractArrayResult);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotImplementsTagInterface(): void
    {
        $abstractArrayResult = $this->createStub(AbstractArrayResult::class);
        $this->assertNotInstanceOf(TagInterface::class, $abstractArrayResult);
    }

    /**
     * @psalm-return iterable<string,array{array: ArrayLike, actual: bool}>
     */
    public static function provAbstractArrayResult(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => [],
            'actual' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new \ArrayObject([]),
            'actual' => true,
        ];
    }

    /**
     * @dataProvider provAbstractArrayResult
     *
     * @psalm-param ArrayLike $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAbstractArrayResult(iterable $array, bool $actual): void
    {
        $arrayResult = new class($array, $actual) extends AbstractArrayResult {
            /**
             * @var bool
             *
             * @psalm-readonly
             */
            private $actual;

            /** @psalm-param ArrayLike $array */
            public function __construct(iterable $array, bool $actual)
            {
                parent::__construct($array);
                $this->actual = $actual;
            }

            public function actual(): bool
            {
                return $this->actual;
            }
        };

        $this->assertSame($actual, $arrayResult->actual());
        $expect = is_array($array) ? $array : iterator_to_array($array);
        $this->assertSame($expect, iterator_to_array($arrayResult));
    }
}
// vim: syntax=php sw=4 ts=4 et:
