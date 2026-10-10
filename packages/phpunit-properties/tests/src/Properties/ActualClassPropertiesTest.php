<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\Result\ResultInterface;
use Tailors\PHPUnit\Result\ResultInterfaceTestTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Properties\ActualClassProperties
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type CtorArgs from AbstractClassPropertiesTestCase
 */
final class ActualClassPropertiesTest extends AbstractClassPropertiesTestCase
{
    use ResultInterfaceTestTrait;

    /**
     * @psalm-return iterable<string, array{
     *  object: ResultInterface,
     *  actual: mixed
     * }>
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
     * @psalm-return ActualClassProperties
     */
    public static function getObject(array $ctorArgs): iterable
    {
        return new ActualClassProperties(...$ctorArgs);
    }

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<ActualClassProperties>
     */
    public static function getClass(): string
    {
        return ActualClassProperties::class;
    }
}
// vim: syntax=php sw=4 ts=4 et:
