<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use Tailors\PHPUnit\ArrayResult\ArrayResultInterface;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;

final class DummyAbstractRecursiveConstraint extends AbstractRecursiveConstraint
{
    public static function create(
        ArrayResultInterface $expected,
        ComparatorInterface $comparator,
        ValueSelectorInterface $valueSelector,
        RecursiveUnwrapperInterface $unwrapper
    ): self {
        return new self($expected, $comparator, $valueSelector, $unwrapper);
    }
}
