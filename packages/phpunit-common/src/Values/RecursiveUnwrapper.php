<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\CircularDependencyException;

/**
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveUnwrapper implements RecursiveUnwrapperInterface
{
    public const UNIQUE_TAG = 'unwrapped-values:$1$zIlgusJc$ZZCyNRPOX1SbpKdzoD2hU/';

    /**
     * @var \SplObjectStorage
     */
    private $seen;

    /**
     * @var bool
     */
    private $tagging;

    /**
     * Initializes the object.
     *
     * @param bool $tagging
     *                      If true, then a unique tag will be appended to the end of every
     *                      array that results from unwrapping of array of properties
     */
    public function __construct(bool $tagging = true)
    {
        $this->tagging = $tagging;
        $this->seen = new \SplObjectStorage();
    }

    /**
     * Walk recursively through $values and unwrap nested instances of
     * ValuesInterface when suitable.
     *
     * @throws CircularDependencyException
     */
    public function unwrap(ValuesInterface $values): array
    {
        $this->seen = new \SplObjectStorage();

        try {
            $result = $this->walkRecursive($values);
        } finally {
            $this->seen = new \SplObjectStorage();
        }

        return $result;
    }

    private function walkRecursive(ValuesInterface $current): array
    {
        $array = $current->getArrayCopy();
        $this->seen->attach($current);

        try {
            array_walk_recursive($array, [$this, 'visit'], $current);
        } finally {
            $this->seen->detach($current);
        }

        if ($this->tagging) {
            // Distinguish unwrapped properties from regular arrays
            // by adding UNIQUE TAG AT THE END of $array.
            $array[self::UNIQUE_TAG] = true;
        }

        return $array;
    }

    /**
     * @param mixed $value
     * @param mixed $key
     *
     * @psalm-param array-key $key
     *
     * @throws CircularDependencyException
     */
    private function visit(&$value, $key, ValuesInterface $parent): void
    {
        if ($value instanceof SelectionAggregateInterface) {
            $value = $value->getSelection();
        }

        if ($value instanceof ValuesInterface && $parent->actual() === $value->actual()) {
            if ($this->seen->contains($value)) {
                // circular dependency
                $this->throwCircular($key);
            }
            $value = $this->walkRecursive($value);
        }
    }

    /**
     * @param int|string $key
     *
     * @throws CircularDependencyException
     */
    private function throwCircular($key): void
    {
        $id = is_string($key) ? "'".addslashes($key)."'" : $key;

        throw new CircularDependencyException("Circular dependency found in nested properties at key {$id}.");
    }
}

// vim: syntax=php sw=4 ts=4 et:
