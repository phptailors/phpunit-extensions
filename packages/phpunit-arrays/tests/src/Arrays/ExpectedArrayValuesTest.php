<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArrayResult\AbstractArrayResultTestCase
 * @covers \Tailors\PHPUnit\Arrays\AbstractArrayValues
 * @covers \Tailors\PHPUnit\Arrays\AbstractArrayValuesTestCase
 * @covers \Tailors\PHPUnit\Arrays\ExpectedArrayValues
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type CtorArgs from AbstractArrayValuesTestCase
 */
final class ExpectedArrayValuesTest extends AbstractArrayValuesTestCase
{
    /**
     * @psalm-pure
     */
    public static function getActual(): bool
    {
        return false;
    }

    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return ExpectedArrayValues
     */
    public static function getObject(array $ctorArgs): iterable
    {
        return new ExpectedArrayValues(...$ctorArgs);
    }

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<ExpectedArrayValues>
     */
    public static function getClass(): string
    {
        return ExpectedArrayValues::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
