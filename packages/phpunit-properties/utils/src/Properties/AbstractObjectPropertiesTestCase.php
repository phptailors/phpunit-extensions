<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\Common\AbstractArrayObjectTestCase;
use Tailors\PHPUnit\Common\ExportableNameInterface;
use Tailors\PHPUnit\Common\ExportableNameInterfaceTestTrait;
use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\TagInterface;
use Tailors\PHPUnit\Common\TagInterfaceTestTrait;
use Tailors\PHPUnit\Common\TypesInterface;

/**
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
abstract class AbstractObjectPropertiesTestCase extends AbstractArrayObjectTestCase
{
    use ExportableNameInterfaceTestTrait;
    use TagInterfaceTestTrait;

    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return AbstractObjectProperties
     */
    abstract public static function getObject(array $ctorArgs): iterable;

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<AbstractObjectProperties>
     */
    abstract public static function getClass(): string;

    /**
     * @psalm-return iterable<string, array{
     *  object: TagInterface,
     *  tag: mixed
     * }>
     */
    public static function provTag(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'object' => static::getObject([]),
            'tag'    => StaticRandomStrings::familyTag(__NAMESPACE__.'\ObjectProperties', '0f1d9297ad4259f9b8926b83329b4d82448592cd'),
        ];
    }

    /**
     * @psalm-return iterable<string, array{object: object}>
     */
    public static function provImplementsTagInterface(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'object' => static::getObject([]),
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *  class: class-string<ExportableNameInterface>,
     *  name: mixed
     * }>
     */
    public static function provExportableName(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'class' => static::getClass(),
            'name'  => 'ObjectProperties',
        ];
    }

    /**
     * @psalm-return iterable<string, array{object: object}>
     */
    public static function provImplementsExportableNameInterface(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'object' => static::getObject([]),
        ];
    }
}
// vim: syntax=php sw=4 ts=4 et:
