<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\Common\ReferenceStorage;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveUnwrapper implements RecursiveUnwrapperInterface
{
    public const UNIQUE_TAG = 'unwrapped-values:$1$zIlgusJc$ZZCyNRPOX1SbpKdzoD2hU/';

    /**
     * @var ReferenceStorage
     */
    private $seen;

    /**
     * @var bool
     */
    private $tagging;

    /**
     * @var list<array-key>
     */
    private $path;

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
        $this->seen = new ReferenceStorage();
        $this->path = [];
    }

    /**
     * Walk recursively through $values and unwrap nested instances of
     * ValuesInterface when suitable.
     *
     * @throws CircularDependencyException
     */
    #[\Override]
    public function unwrap(ValuesInterface $values): array
    {
        $this->seen = new ReferenceStorage();
        $this->path = [];

        try {
            $result = $this->walkRecursive($values);
        } finally {
            $this->seen = new ReferenceStorage();
            $this->path = [];
        }

        return $result;
    }

    /**
     * @throws CircularDependencyException
     */
    private function walkRecursive(ValuesInterface $current): array
    {
        if ($this->seen->contains($current)) {
            // circular dependency
            $this->throwCircular();
        }

        $array = $current->getArrayCopy();
        $this->seen->add($current);

        try {
            $this->arrayWalkRecursive($array, $current);
        } finally {
            $this->seen->remove($current);
        }

        if ($this->tagging) {
            // Distinguish unwrapped properties from regular arrays
            // by adding UNIQUE TAG AT THE END of $array.
            $array[self::UNIQUE_TAG] = true;
        }

        return $array;
    }

    /**
     * @param array $array
     *
     * @throws CircularDependencyException
     */
    private function arrayWalkRecursive(array &$array, ValuesInterface $parent): void
    {
        $this->seen->add($array);

        try {
            /** @var mixed $value */
            foreach ($array as $key => &$value) {
                array_push($this->path, $key);

                try {
                    if (is_array($value)) {
                        $this->arrayWalkRecursive($value, $parent);
                    } else {
                        $this->visit($value, $parent);
                    }
                } finally {
                    array_pop($this->path);
                }
            }
        } finally {
            $this->seen->remove($array);
        }
    }

    /**
     * @param mixed $value
     *
     * @throws CircularDependencyException
     */
    private function visit(&$value, ValuesInterface $parent): void
    {
        if ($value instanceof ValuesWrapperInterface) {
            $value = $value->getValues();
        }

        if ($value instanceof ValuesInterface && $parent->actual() === $value->actual()) {
            $value = $this->walkRecursive($value);
        }
    }

    /**
     * @return never
     *
     * @throws CircularDependencyException
     */
    private function throwCircular(): void
    {
        throw new CircularDependencyException("Circular dependency found in nested values at \$values{$this->pathString()}.");
    }

    /**
     * @psalm-mutation-free
     */
    private function pathString(): string
    {
        return implode('', array_map(function ($key) {
            return '['.(is_string($key) ? ('"'.addslashes($key).'"') : (string) $key).']';
        }, $this->path));
    }
}

// vim: syntax=php sw=4 ts=4 et:
