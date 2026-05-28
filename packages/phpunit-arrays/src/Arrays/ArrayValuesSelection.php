<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\ArraySpec\AbstractArraySelectionSpec;
use Tailors\PHPUnit\ValueSelector\ArrayValueSelector;

/**
 * An array of expected class properties.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 *
 * @template-extends AbstractArraySelectionSpec<\Traversable, class-string>
 */
final class ArrayValuesSelection extends AbstractArraySelectionSpec
{
    /**
     * @psalm-param ArrayLike $array
     */
    public function __construct(iterable $array)
    {
        parent::__construct(new ArrayValuesFactory(), new ArrayValueSelector(), $array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
