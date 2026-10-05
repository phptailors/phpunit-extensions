<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use Tailors\PHPUnit\Arrays\ArrayValuesSelection;
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @psalm-import-type ArrayLike from TypesInterface
 */
trait ArrayValuesTrait
{
    /**
     * Returns an object representing array array values.
     *
     * @psalm-param ArrayLike $array
     */
    public static function arrayValues(iterable $array): ArrayValuesSelection
    {
        return new ArrayValuesSelection($array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
