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
            $this->walkRecursive($values, $visitor);
        } finally {
            $this->seen = new ReferenceStorage();
            $this->path = [];
        }
    }

    /**
     * @param array|ValuesInterface $values
     *
     * @psalm-template T of array|ValuesInterface
     *
     * @psalm-param T $values
     *
     * @psalm-param-out T $values
     */
    private function walkRecursive(&$values, RecursiveVisitorInterface $visitor): void
    {
        if ($this->seen->contains($values)) {
            if (!$visitor->cycle($values, $this->path)) {
                return;
            }
        }

        $this->seen->add($values);

        try {
            $iterate = $visitor->enter($values, $this->path);
            if ($iterate) {
                $this->iterate($values, $visitor);
            } else {
                $visitor->visit($values, $this->path, false);
            }
            $visitor->leave($values, $this->path, $iterate);
        } finally {
            $this->seen->remove($values);
        }
    }

    /**
     * @param array|ValuesInterface $values
     */
    private function iterate($values, RecursiveVisitorInterface $visitor): void
    {
        /** @var mixed $value */
        foreach ($values as $key => &$value) {
            array_push($this->path, $key);

            try {
                $this->visitValue($value, $visitor);
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
    private function visitValue(&$value, RecursiveVisitorInterface $visitor): void
    {
        if (is_array($value)) {
            $this->walkRecursive($value, $visitor);

            return;
        }

        if (!$this->noUnwrapValuesWrappers && $value instanceof ValuesWrapperInterface) {
            $node = $value->getValues();
        } else {
            $node = $value;
        }

        if (!$this->noWalkNestedValuesInterface && $node instanceof ValuesInterface) {
            $this->walkRecursive($node, $visitor);

            return;
        }

        // Leaf node
        $visitor->visit($node, $this->path, true);
    }
}

// vim: syntax=php sw=4 ts=4 et:
