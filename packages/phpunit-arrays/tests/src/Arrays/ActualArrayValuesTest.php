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
 * @covers \Tailors\PHPUnit\Arrays\ActualArrayValues
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type CtorArgs from AbstractArrayValuesTestCase
 */
final class ActualArrayValuesTest extends AbstractArrayValuesTestCase
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
     * @psalm-return ActualArrayValues
     */
    public static function getObject(array $ctorArgs): iterable
    {
        return new ActualArrayValues(...$ctorArgs);
    }

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<ActualArrayValues>
     */
    public static function getClass(): string
    {
        return ActualArrayValues::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
