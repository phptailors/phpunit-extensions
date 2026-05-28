<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\ArrayResult\AbstractArrayResult;
use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\TagInterface;

/**
 * An array of expected or actual class properties.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
abstract class AbstractClassProperties extends AbstractArrayResult implements TagInterface
{
    /**
     * @param array|\Traversable $array
     */
    final public function __construct($array = [])
    {
        parent::__construct($array);
    }

    /**
     * @psalm-return non-empty-string
     */
    final public function tag(): string
    {
        return StaticRandomStrings::familyTag(__NAMESPACE__.'\ClassProperties', '204ae0e60189915fbbb65c711dff84ddb77710c6');
    }
}

// vim: syntax=php sw=4 ts=4 et:
