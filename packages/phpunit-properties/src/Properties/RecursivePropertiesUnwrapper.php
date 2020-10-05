<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

/**
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursivePropertiesUnwrapper implements RecursivePropertiesUnwrapperInterface
{
    public const UNIQUE_TAG = 'unwrapped-properties:$1$zIlgusJc$ZZCyNRPOX1SbpKdzoD2hU/';

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
     * Returns whether the algorithm is tagging the unwrapped arrays.
     */
    public function isTagging(): bool
    {
        return $this->tagging;
    }

    /**
     * Walk recursively through $properties and unwrap nested instances of
     * PropertiesInterface when suitable.
     *
     * A call to $properties->canUnwrapChild($child) is made to decide whether
     * to unwrap given $child as well.
     *
     * @throws CircularDependencyException
     */
    public function unwrap(PropertiesInterface $properties): array
    {
        $this->seen = new \SplObjectStorage();

        try {
            $result = $this->walkRecursive($properties);
        } finally {
            $this->seen = new \SplObjectStorage();
        }

        return $result;
    }

    private function walkRecursive(PropertiesInterface $current): array
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
     * @throws CircularDependencyException
     */
    private function visit(&$value, $key, PropertiesInterface $parent): void
    {
        if ($value instanceof PropertiesInterface && $parent->canUnwrapChild($value)) {
            if ($this->seen->contains($value)) {
                // circular dependency
                $this->throwCircular($key);
            }
            $value = $this->walkRecursive($value);
        }
    }

    /**
     * @param int|string $key
     * @throws CircularDependencyException
     */
    private function throwCircular($key): void
    {
        $id = is_string($key) ? "'".addslashes($key)."'" : $key;

        throw new CircularDependencyException("Circular dependency found in nested properties at key {$id}.");
    }
}

// vim: syntax=php sw=4 ts=4 et:
