<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\Properties\ObjectPropertiesSelection;

/**
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
trait ObjectPropertiesTrait
{
    /**
     * Returns an object representing expected array values.
     *
     * @psalm-param ArrayLike $array
     */
    public static function objectProperties(iterable $array): ObjectPropertiesSelection
    {
        return new ObjectPropertiesSelection($array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
