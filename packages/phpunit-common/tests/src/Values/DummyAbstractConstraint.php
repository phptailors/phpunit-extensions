<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\Comparator\ComparatorInterface;

final class DummyAbstractConstraint extends AbstractConstraint
{
    public static function create(
        ComparatorInterface $comparator,
        SelectionInterface $expected,
        RecursiveUnwrapperInterface $unwrapper
    ): self {
        return new self($comparator, $expected, $unwrapper);
    }
}
