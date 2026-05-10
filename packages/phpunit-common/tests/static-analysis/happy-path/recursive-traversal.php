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
     * @var array|ValuesInterface
     *
     * @psalm-readonly
     */
    private $node;

    /**
     * @var array-key
     *
     * @psalm-readonly
     */
    private $key;

    /**
     * @param array|ValuesInterface $node
     * @param array-key             $key
     */
    public function __construct($node, $key)
    {
        $this->node = $node;
        $this->key = $key;
    }

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
     * @param array|ValuesInterface $node
     * @param list<StackItem>       $stack
     */
    public function enter($node, array $stack): bool
    {
        return true;
    }

    /**
     * @param array|ValuesInterface $node
     * @param list<StackItem>       $stack
     */
    public function leave($node, array $stack, bool $iterating): void {}

    /**
     * @param mixed           $node
     * @param list<StackItem> $stack
     */
    public function visit($node, array $stack, bool $iterating): void {}

    /**
     * @param array|ValuesInterface $node
     * @param list<StackItem>       $stack
     */
    public function cycle($node, array $stack): bool
    {
        return false;
    }

    /**
     * @param array|ValuesInterface $node
     * @param array-key             $key
     * @param list<StackItem>       $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem($node, $key, array $stack): RecursiveVisitorStackItemInterface
    {
        return new DummyRecursiveVisitorStackItem($node, $key);
    }
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
