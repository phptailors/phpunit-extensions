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
use Tailors\PHPUnit\Common\ExportableNameInterface;
use Tailors\PHPUnit\Common\ExportableNameInterfaceTestTrait;
use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\TagInterface;
use Tailors\PHPUnit\Common\TagInterfaceTestTrait;
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\AbstractKsortedArray
 * @covers \Tailors\PHPUnit\Arrays\AbstractKsortedArrayTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type CtorArgs = list{0?: ArrayLike}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class AbstractKsortedArrayTest extends TestCase
{
    use ExportableNameInterfaceTestTrait;
    use TagInterfaceTestTrait;

    /**
     * @psalm-return iterable<string, array{
     *  object: TagInterface,
     *  tag: mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provTag(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'object' => self::getTestableObject([]),
            'tag'    => StaticRandomStrings::familyTag(__NAMESPACE__.'\KsortedArray', 'e81092c9084f0813af075edd32d0beb2f3c774f3'),
        ];
    }

    /**
     * @psalm-return iterable<string, array{object: object}>
     *
     * @codeCoverageIgnore
     */
    public static function provImplementsTagInterface(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'object' => self::getTestableObject([]),
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *  class: class-string<ExportableNameInterface>,
     *  name: mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provExportableName(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'class' => AbstractKsortedArray::class,
            'name'  => 'KsortedArray',
        ];
    }

    /**
     * @psalm-return iterable<string, array{object: object}>
     *
     * @codeCoverageIgnore
     */
    public static function provImplementsExportableNameInterface(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'object' => self::getTestableObject([]),
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      array: ArrayLike
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provArrayObject(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'array' => ['x' => 'X'],
        ];
    }

    /**
     * @dataProvider provArrayObject
     *
     * @psalm-param ArrayLike $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testArrayObject(iterable $array): void
    {
        $arrayValues = self::getTestableObject($array);

        $expected = is_array($array) ? $array : iterator_to_array($array);

        self::assertSame($expected, iterator_to_array($arrayValues));
    }

    /**
     * @psalm-param ArrayLike $array
     *
     * @codeCoverageIgnore
     */
    private static function getTestableObject(iterable $array): AbstractKsortedArray
    {
        return new class($array) extends AbstractKsortedArray {};
    }
}

// vim: syntax=php sw=4 ts=4 et:
