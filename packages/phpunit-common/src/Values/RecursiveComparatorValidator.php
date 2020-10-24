<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\ComparatorWrapperInterface;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * Given an array of expectations, searches it recursivelly for constraints of
 * incompatible type.
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveComparatorValidator
{
    /**
     * @var ComparatorInterface
     */
    private $comparator;

    /**
     * @var \SplObjectStorage
     */
    private $seen;

    /**
     * @var int
     */
    private $failures;

    public function __construct(ComparatorInterface $comparator)
    {
        $this->comparator = $comparator;
        $this->seen = new \SplObjectStorage();
        $this->failures = 0;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validate(array $expected, int $argument, int $distance = 1): void
    {
        $this->seen = new \SplObjectStorage();
        $this->failures = 0;

        try {
            $this->validateRecursive($expected);
            if ($this->failures) {
                $this->throwInvalidArgumentException($this->failures, $argument, 1 + $distance);
            }
        } finally {
            $this->seen = new \SplObjectStorage();
            $this->failures = 0;
        }
    }

    private function validateRecursive(array $current): void
    {
        array_walk_recursive($current, [$this, 'visit']);
    }

    /**
     * @param mixed $value
     */
    private function visit($value): void
    {
        if ($value instanceof ComparatorWrapperInterface) {
            $this->visitComparator($value->getComparator());
        }

        if ($value instanceof SelectionWrapperInterface) {
            $this->visitSelection($value->getSelection());
        }
    }

    private function visitComparator(ComparatorInterface $comparator): void
    {
        if (get_class($comparator) !== get_class($this->comparator)) {
            ++$this->failures;
        }
    }

    private function visitSelection(SelectionInterface $selection): void
    {
        if ($this->seen->contains($selection)) {
            return; // circular dependency
        }

        $this->seen->attach($selection);

        try {
            $this->validateRecursive($selection->getArrayCopy());
        } finally {
            $this->seen->detach($selection);
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function throwInvalidArgumentException(int $failures, int $argument, int $distance): void
    {
        $comparator = (new \ReflectionClass($this->comparator))->getShortName();
        $expect = sprintf('an array with only %s nested comparators', $comparator);
        $actual = sprintf('an array with %d comparator%s of other type', $failures, $failures > 1 ? 's' : '');

        throw InvalidArgumentException::fromBacktrace($argument, $expect, $actual, 1 + $distance);
        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
