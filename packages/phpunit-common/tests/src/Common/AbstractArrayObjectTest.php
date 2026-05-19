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
 * @covers \Tailors\PHPUnit\Common\AbstractArrayObjectTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 */
final class AbstractArrayObjectTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsArrayObject(): void
    {
        $arrayObject = $this->createStub(AbstractArrayObject::class);
        $this->assertInstanceOf(\ArrayObject::class, $arrayObject);
    }

    /**
     * @psalm-return iterable<string,array{array: ArrayLike}>
     *
     * @codeCoverageIgnore
     */
    public static function provAbstractArrayObject(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'array' => ['x' => 'X'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array' => new \ArrayObject(['x' => 'X']),
        ];
    }

    /**
     * @dataProvider provAbstractArrayObject
     *
     * @psalm-param ArrayLike $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAbstractArrayObject(iterable $array): void
    {
        $arrayObject = new class($array) extends AbstractArrayObject {
            /** @psalm-param ArrayLike $array */
            public function __construct(iterable $array)
            {
                parent::__construct($array);
            }
        };

        $expect = is_array($array) ? $array : iterator_to_array($array);
        $this->assertSame($expect, iterator_to_array($arrayObject));
    }
}
// vim: syntax=php sw=4 ts=4 et:
