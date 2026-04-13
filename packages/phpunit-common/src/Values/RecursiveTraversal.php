<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\Common\ReferenceStorage;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveTraversal implements RecursiveTraversalInterface
{
    /**
     * @var ReferenceStorage
     */
    private $seen;

    /**
     * @var list<array-key>
     */
    private $path;

    /**
     * @var bool
     */
    private $noUnwrapValuesWrappers;

    /**
     * @var bool
     */
    private $noWalkNestedValuesInterface;

    /**
     * Initializes the object.
     */
    public function __construct(bool $noUnwrapValuesWrappers = false, bool $noWalkNestedValuesInterface = false)
    {
        $this->seen = new ReferenceStorage();
        $this->path = [];
        $this->noUnwrapValuesWrappers = $noUnwrapValuesWrappers;
        $this->noWalkNestedValuesInterface = $noWalkNestedValuesInterface;
    }

    /**
     * Walk recursively through $values and unwrap nested instances of
     * ValuesInterface when suitable.
     */
    public function walk(ValuesInterface $values, RecursiveVisitorInterface $visitor): void
    {
        $this->seen = new ReferenceStorage();
        $this->path = [];

        try {
            $this->valuesWalkRecursive($values, $visitor);
        } finally {
            $this->seen = new ReferenceStorage();
            $this->path = [];
        }
    }

    private function valuesWalkRecursive(ValuesInterface $values, RecursiveVisitorInterface $visitor): void
    {
        if ($this->seen->contains($values)) {
            if (!$visitor->cycle($values, $this->path)) {
                return;
            }
        }

        $this->seen->add($values);

        try {
            if ($visitor->visit($values, $this->path)) {
                $array = $values->getArrayCopy();
                $this->arrayIterate($array, $visitor);
            }
        } finally {
            $this->seen->remove($values);
        }
    }

    /**
     * @param array $array
     *
     * @psalm-template T of array
     *
     * @psalm-param T $array
     *
     * @psalm-param-out T $array
     */
    private function arrayWalkRecursive(array &$array, RecursiveVisitorInterface $visitor): void
    {
        if ($this->seen->contains($array)) {
            if (!$visitor->cycle($array, $this->path)) {
                return;
            }
        }

        $this->seen->add($array);

        try {
            if ($visitor->visit($array, $this->path)) {
                $this->arrayIterate($array, $visitor);
            }
        } finally {
            $this->seen->remove($array);
        }
    }

    /**
     * @param array $array
     */
    private function arrayIterate(array $array, RecursiveVisitorInterface $visitor): void
    {
        /** @var mixed $value */
        foreach ($array as $key => &$value) {
            array_push($this->path, $key);

            try {
                $this->arrayVisitValue($value, $visitor);
            } finally {
                array_pop($this->path);
            }
        }
    }

    /**
     * @param mixed $value
     *
     * @psalm-template T
     *
     * @psalm-param T $value
     *
     * @psalm-param-out T $value
     */
    private function arrayVisitValue(&$value, RecursiveVisitorInterface $visitor): void
    {
        if (is_array($value)) {
            $this->arrayWalkRecursive($value, $visitor);

            return;
        }

        if (!$this->noUnwrapValuesWrappers && $value instanceof ValuesWrapperInterface) {
            $node = $value->getValues();
        } else {
            $node = $value;
        }

        if (!$this->noWalkNestedValuesInterface && $node instanceof ValuesInterface) {
            $this->valuesWalkRecursive($node, $visitor);

            return;
        }

        // Leaf node
        $visitor->visit($node, $this->path);
    }
}

// vim: syntax=php sw=4 ts=4 et:
