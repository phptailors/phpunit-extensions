<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\InternalErrorException;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorStackItemInterface;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorUtils;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type StackItem = RecursiveExpectedResultFactoryStackItem
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
final class RecursiveExpectedResultFactoryVisitor implements RecursiveExpectedResultFactoryVisitorInterface
{
    /**
     * @var mixed
     */
    private $result;

    /**
     * @var ?array|\ArrayAccess
     */
    private $current;

    public function begin(): void
    {
        $this->result = null;
        $this->current = null;
    }

    public function end(): void
    {
        $this->current = null;
    }

    /**
     * @return mixed
     *
     * @psalm-mutation-free
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * @psalm-param ArrayLike       $node
     * @psalm-param list<StackItem> $stack
     */
    public function enter(iterable $node, array $stack): bool
    {
        if ($node instanceof ResultFactoryWrapperInterface) {
            $factory = $node->getResultFactory();

            if (!$factory->supports($node)) {
                return false;
            }

            /** @psalm-suppress MissingThrowsDocblock */
            $result = $factory->getResult(false, $node);

            if (!$result instanceof \ArrayAccess) {
                return false;
            }

            $this->current = $result;

            return true;
        }

        if (!is_array($node)) {
            return false;
        }

        $this->current = $node;

        return true;
    }

    /**
     * @psalm-param ArrayLike       $node
     * @psalm-param list<StackItem> $stack
     */
    public function leave(iterable $node, array $stack, bool $iterating): void
    {
        if (!$iterating) {
            return;
        }

        if (null === $this->current) {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace('$this->current is null');
        }

        $this->set($stack, $this->current);
    }

    /**
     * @param mixed $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function visit($node, array $stack, bool $iterating): void
    {
        $this->set($stack, $node);
    }

    /**
     * @throws CircularDependencyException
     *
     * @psalm-param ArrayLike       $node
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-return never
     */
    public function cycle(iterable $node, array $stack): bool
    {
        self::throwCircular($stack);
    }

    /**
     * @param mixed $key
     *
     * @psalm-param ArrayLike       $node
     * @psalm-param array-key       $key
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem(iterable $node, $key, array $stack): RecursiveVisitorStackItemInterface
    {
        if (null === $this->current) {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace('$this->current is null');
        }

        return new RecursiveExpectedResultFactoryStackItem($node, $key, $this->current);
    }

    /**
     * @psalm-param StackItem       $item
     * @psalm-param list<StackItem> $stack
     */
    public function freeStackItem(RecursiveVisitorStackItemInterface $item, array $stack): void
    {
        $this->current = $item->result();
    }

    /**
     * @return never
     *
     * @throws CircularDependencyException
     *
     * @psalm-param list<StackItem> $stack
     */
    private static function throwCircular(array $stack): void
    {
        $pathString = RecursiveVisitorUtils::pathAsString($stack);

        throw new CircularDependencyException("Circular dependency found in nested array at \$array{$pathString}.");
    }

    /**
     * @param mixed $result
     *
     * @psalm-param list<StackItem> $stack
     */
    private function set(array $stack, $result): void
    {
        if (0 === ($count = count($stack))) {
            $this->result = $result;

            return;
        }

        $stack[$count - 1]->set($result);
    }
}

// vim: syntax=php sw=4 ts=4 et:
