<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\ArrayResult\AbstractArrayResultTestCase;


/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArrayResult\AbstractArrayResultTestCase
 * @covers \Tailors\PHPUnit\Arrays\AbstractKsortedArray
 * @covers \Tailors\PHPUnit\Arrays\AbstractKsortedArrayTestCase
 * @covers \Tailors\PHPUnit\Arrays\ActualKsortedArray
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type CtorArgs from AbstractArrayResultTestCase
 *
 * @var AbstractArrayResultTestCase $__phpactor__workaround__unused_import__AbstractArrayResultTestCase
 */
final class ActualKsortedArrayTest extends AbstractKsortedArrayTestCase
{
    /**
     * @psalm-pure
     */
    public static function getActual(): bool
    {
        return true;
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
