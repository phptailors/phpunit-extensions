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
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
abstract class KsortedArrayTestCase extends AbstractArrayResultTestCase
{
    final public static function getArrayResultFamilyName(): string
    {
        return __NAMESPACE__.'\KsortedArray';
    }
}
// vim: syntax=php sw=4 ts=4 et:
