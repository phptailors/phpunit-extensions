<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Common\AbstractArrayObjectTestCase;
use Tailors\PHPUnit\Result\ResultInterface;
use Tailors\PHPUnit\Result\ResultInterfaceTestTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\ActualKsortedArray
 * @covers \Tailors\PHPUnit\Arrays\ActualKsortedArrayTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type CtorArgs from AbstractArrayObjectTestCase
 *
 * @var AbstractArrayObjectTestCase $__phpactor__workaround__unused_import__AbstractArrayResultTestCase
 */
final class ActualKsortedArrayTest extends AbstractKsortedArrayTestCase
{
    use ResultInterfaceTestTrait;

    /**
     * @psalm-return iterable<string, array{
     *  object: ResultInterface,
     *  actual: mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provActual(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'object' => self::getObject([]),
            'actual' => true,
        ];
    }

    /**
     * @psalm-return iterable<string, array{object: object}>
     *
     * @codeCoverageIgnore
     */
    public static function provImplementsResultInterface(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'object' => self::getObject([]),
        ];
    }

    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return ActualKsortedArray
     */
    public static function getObject(array $ctorArgs): iterable
    {
        return new ActualKsortedArray(...$ctorArgs);
    }

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<ActualKsortedArray>
     */
    public static function getClass(): string
    {
        return ActualKsortedArray::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
