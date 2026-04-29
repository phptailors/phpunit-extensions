<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\Comparator;

use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\ComparatorWrapperInterface;

final class DummyComparator implements ComparatorInterface
{
    /**
     * @param mixed $left
     * @param mixed $right
     */
    public function compare($left, $right): bool
    {
        return false;
    }

    public function adjective(): string
    {
        return '';
    }
}

final class DummyComparatorWrapper implements ComparatorWrapperInterface
{
    public function __construct(public ComparatorInterface $comparator) {}

    public function getComparator(): ComparatorInterface
    {
        return $this->comparator;
    }
}

function consume(): ComparatorInterface
{
    $wrapper = new DummyComparatorWrapper(new DummyComparator());

    return $wrapper->getComparator();
}

// vim: syntax=php sw=4 ts=4 et:
