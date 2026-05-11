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
 *
 * @psalm-template VisitorStackItem of RecursiveVisitorStackItemInterface
 */
final class RecursiveTraversal implements RecursiveTraversalInterface
{
    private ReferenceStorage $seen;

    /**
     * @var list<VisitorStackItem>
     */
    private array $stack;

    /**
     * Initializes the object.
     */
    public function __construct(private readonly bool $noUnwrapValuesWrappers = false, private readonly bool $noWalkNestedValuesInterface = false, private readonly bool $noWalkNestedArrays = false)
    {
        $this->seen = new ReferenceStorage();
        $this->stack = [];
    }

    /**
     * Walk recursively through $values and unwrap nested instances of
     * ValuesInterface when suitable.
     *
     * @psalm-template StackItem of RecursiveVisitorStackItemInterface
     *
     * @psalm-param RecursiveVisitorInterface<StackItem> $visitor
     */
    public function walk(ValuesInterface $values, RecursiveVisitorInterface $visitor): void
    {
        $this->seen = new ReferenceStorage();
        $this->stack = [];

        try {
            /** @var self<StackItem> $this */
            $this->walkRecursive($values, $visitor);
        } finally {
            $this->seen = new ReferenceStorage();
            $this->stack = [];
        }
    }

    /**
     * @psalm-template T of array|ValuesInterface
     * @psalm-template StackItem of RecursiveVisitorStackItemInterface
     *
     * @psalm-param T $node
     * @psalm-param RecursiveVisitorInterface<StackItem> $visitor
     *
     * @psalm-param-out T $node
     *
     * @psalm-if-this-is self<StackItem>
     */
    private function walkRecursive(array|ValuesInterface &$node, RecursiveVisitorInterface $visitor): void
    {
        if ($this->seen->contains($node)) {
            if (!$visitor->cycle($node, $this->stack)) {
                return;
            }
        }

        $this->seen->add($node);

        try {
            $iterate = $visitor->enter($node, $this->stack);
            if ($iterate) {
                $this->iterate($node, $visitor);
            } else {
                $visitor->visit($node, $this->stack, false);
            }
            $visitor->leave($node, $this->stack, $iterate);
        } finally {
            $this->seen->remove($node);
        }
    }

    /**
     * @psalm-template StackItem of RecursiveVisitorStackItemInterface
     *
     * @psalm-param RecursiveVisitorInterface<StackItem> $visitor
     *
     * @psalm-if-this-is self<StackItem>
     */
    private function iterate(array|ValuesInterface $node, RecursiveVisitorInterface $visitor): void
    {
        /** @var mixed $value */
        foreach ($node as $key => &$value) {
            array_push($this->stack, $visitor->makeStackItem($node, $key, $this->stack));

            try {
                $this->visitValue($value, $visitor);
            } finally {
                $item = array_pop($this->stack);
            }

            $visitor->freeStackItem($item, $this->stack);
        }
    }

    /**
     * @psalm-template T
     * @psalm-template StackItem of RecursiveVisitorStackItemInterface
     *
     * @psalm-param T $value
     * @psalm-param RecursiveVisitorInterface<StackItem> $visitor
     *
     * @psalm-param-out T $value
     *
     * @psalm-if-this-is self<StackItem>
     */
    private function visitValue(mixed &$value, RecursiveVisitorInterface $visitor): void
    {
        if (!$this->noWalkNestedArrays && is_array($value)) {
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
        $visitor->visit($node, $this->stack, true);
    }
}

// vim: syntax=php sw=4 ts=4 et:
