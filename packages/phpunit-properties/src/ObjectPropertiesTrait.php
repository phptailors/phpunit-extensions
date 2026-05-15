<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use Tailors\PHPUnit\Properties\ExpectedObjectProperties;
use Tailors\PHPUnit\Values\ValuesInterface;

trait ObjectPropertiesTrait
{
    /**
     * Returns an object representing expected array values.
     *
     * @param array|\Traversable $array
     */
    public static function objectProperties($array): ValuesInterface
    {
        return new ExpectedObjectProperties($array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
