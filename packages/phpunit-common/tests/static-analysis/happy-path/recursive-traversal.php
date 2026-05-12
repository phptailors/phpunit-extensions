<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\RecursiveTraversal;

use Tailors\PHPUnit\Values\RecursiveTraversal;
use Tailors\PHPUnit\Values\RecursiveVisitorInterface;
use Tailors\PHPUnit\Values\RecursiveVisitorStackItemInterface;
use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class DummyRecursiveVisitorStackItem implements RecursiveVisitorStackItemInterface
{
    /**
     * @param array|ValuesInterface $node
     * @param mixed                 $key
     *
     * @psalm-param array-key             $key
     */
    public function __construct(private readonly array|ValuesInterface $node, private readonly mixed $key) {}

    /**
     * @return mixed
     *
     * @psalm-return array|ValuesInterface
     *
     * @psalm-mutation-free
     */
    public function node()
    {
        return $this->node;
    }

    /**
     * @return array-key
     *
     * @psalm-mutation-free
     */
    public function key()
    {
        return $this->key;
    }
}

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit\StaticAnalysis\HappyPath\RecursiveTraversal
 *
 * @template-implements RecursiveVisitorInterface<DummyRecursiveVisitorStackItem>
 *
 * @psalm-type StackItem = DummyRecursiveVisitorStackItem
 */
final class DummyRecursiveVisitor implements RecursiveVisitorInterface
{
    /**
     * @psalm-param list<StackItem>       $stack
     */
    public function enter(array|ValuesInterface $node, array $stack): bool
    {
        return true;
    }

    /**
     * @psalm-param list<StackItem>       $stack
     */
    public function leave(array|ValuesInterface $node, array $stack, bool $iterating): void {}

    /**
     * @param array $stack
     *
     * @psalm-param list<StackItem> $stack
     */
    public function visit(mixed $node, array $stack, bool $iterating): void {}

    /**
     * @psalm-param list<StackItem>       $stack
     */
    public function cycle(array|ValuesInterface $node, array $stack): bool
    {
        return false;
    }

    /**
     * @param mixed $key
     *
     * @psalm-param array-key             $key
     * @psalm-param list<StackItem>       $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem(array|ValuesInterface $node, $key, array $stack): RecursiveVisitorStackItemInterface
    {
        return new DummyRecursiveVisitorStackItem($node, $key);
    }

    /**
     * @param RecursiveVisitorStackItemInterface $item
     * @param array                              $stack
     *
     * @psalm-param StackItem $item
     * @psalm-param list<StackItem> $stack
     */
    public function freeStackItem(RecursiveVisitorStackItemInterface $item, array $stack): void {}
}

/**
 * @extends \ArrayObject<array-key,mixed>
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit\StaticAnalysis\HappyPath\RecursiveTraversal
 */
final class DummyValues extends \ArrayObject implements ValuesInterface
{
    public function actual(): bool
    {
        return false;
    }
}

function consume(): RecursiveTraversal
{
    $traversal = new RecursiveTraversal();
    $visitor = new DummyRecursiveVisitor();
    $values = new DummyValues();

    $traversal->walk($values, $visitor);

    return $traversal;
}

// vim: syntax=php sw=4 ts=4 et:
