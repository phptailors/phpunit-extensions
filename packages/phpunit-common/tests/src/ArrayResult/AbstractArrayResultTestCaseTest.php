<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\TagInterface;
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ArrayResult\AbstractArrayResultTestCase
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 */
final class AbstractArrayResultTestCaseTest extends AbstractArrayResultTestCase
{
    /**
     * @psalm-param list{0?:ArrayLike} $ctorArgs
     *
     * @psalm-return ArrayLike
     */
    public static function getArrayResultObject(array $ctorArgs): iterable
    {
        return new class(...$ctorArgs) extends AbstractArrayResult implements TagInterface {
            /** @psalm-param ArrayLike $array */
            public function __construct(iterable $array = [])
            {
                parent::__construct($array);
            }

            public function actual(): bool
            {
                return true;
            }

            public function tag(): string
            {
                return StaticRandomStrings::familyTag('SmithArrayFC4IB');
            }
        };
    }

    public static function getArrayResultFamilyName(): string
    {
        return 'SmithArrayFC4IB';
    }

    public static function getArrayResultActual(): bool
    {
        return true;
    }
}
// vim: syntax=php sw=4 ts=4 et:
