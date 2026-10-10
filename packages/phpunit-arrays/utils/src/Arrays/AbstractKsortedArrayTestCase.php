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
 * @psalm-import-type CtorArgs from AbstractArrayResultTestCase
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
abstract class AbstractKsortedArrayTestCase extends AbstractArrayResultTestCase
{
    use ExportableNameInterfaceTestTrait;
    use TagInterfaceTestTrait;

    /**
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-return AbstractKsortedArray
     */
    abstract public static function getObject(array $ctorArgs): iterable;

    /**
     * @psalm-pure
     *
     * @psalm-return class-string<AbstractKsortedArray>
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
            'tag'    => StaticRandomStrings::familyTag(__NAMESPACE__.'\KsortedArray', 'c225435bd5434279f77fb3cddf138302a5c826ec'),
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
            'name'  => 'KsortedArray',
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
