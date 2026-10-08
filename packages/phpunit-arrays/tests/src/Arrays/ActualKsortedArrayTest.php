<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\ActualKsortedArray
 * @covers \Tailors\PHPUnit\Arrays\AbstractKsortedArray
 * @covers \Tailors\PHPUnit\Arrays\KsortedArrayTestCase
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
final class ActualKsortedArrayTest extends KsortedArrayTestCase
{
    public static function getArrayResultActual(): bool
    {
        return true;
    }

    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return ArrayLike
     */
    public static function getArrayResultObject(array $ctorArgs): iterable
    {
        return new ActualKsortedArray(...$ctorArgs);
    }
}
// vim: syntax=php sw=4 ts=4 et:
