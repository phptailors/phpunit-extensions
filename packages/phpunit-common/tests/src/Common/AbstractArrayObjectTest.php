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

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Common\AbstractArrayObject
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class AbstractArrayObjectTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsArrayObject(): void
    {
        $abstractArrayResult = $this->createStub(AbstractArrayObject::class);
        $this->assertInstanceOf(\ArrayObject::class, $abstractArrayResult);
    }

    /**
     * @psalm-return iterable<string,array{array: ArrayLike, actual: bool}>
     */
    public static function provAbstractArrayObject(): iterable
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
     * @dataProvider provAbstractArrayObject
     *
     * @psalm-param ArrayLike $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAbstractArrayObject(iterable $array, bool $actual): void
    {
        $arrayResult = new class($array, $actual) extends AbstractArrayObject {
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
