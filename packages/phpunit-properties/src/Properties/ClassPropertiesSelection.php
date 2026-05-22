<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\ArraySpec\AbstractArraySelectionSpec;
use Tailors\PHPUnit\ValueSelector\ClassPropertySelector;

/**
 * An array of expected class properties.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-extends AbstractArraySelectionSpec<\Traversable, class-string>
 */
final class ClassPropertiesSelection extends AbstractArraySelectionSpec
{
    /**
     * @param array|\Traversable
     */
    public function __construct($array)
    {
        parent::__construct(new ClassPropertiesFactory(), new ClassPropertySelector(), $array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
