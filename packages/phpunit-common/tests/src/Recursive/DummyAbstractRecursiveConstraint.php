<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Recursive;

use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Selector\ValueSelectorInterface;
use Tailors\PHPUnit\Values\ValuesInterface;

final class DummyAbstractRecursiveConstraint extends AbstractRecursiveConstraint
{
    public static function create(
        ValuesInterface $expected,
        ComparatorInterface $comparator,
        ValueSelectorInterface $valueSelector,
        RecursiveUnwrapperInterface $unwrapper
    ): self {
        return new self($expected, $comparator, $valueSelector, $unwrapper);
    }
}
