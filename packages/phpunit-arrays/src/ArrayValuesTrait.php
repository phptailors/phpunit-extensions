<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use Tailors\PHPUnit\Arrays\ExpectedArrayValues;
use Tailors\PHPUnit\Values\ValuesInterface;

trait ArrayValuesTrait
{
    /**
     * Returns an object representing array array values.
     *
     * @param array|\Traversable $array
     */
    public static function arrayValues($array): ValuesInterface
    {
        return new ExpectedArrayValues($array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
