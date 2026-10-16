<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveMapper;

use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorStackItemInterface;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorUtils;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type StackItem   = RecursiveMapperStackItem
 * @psalm-type ArrayMapper = Closure(ArrayLike,list<RecursiveMapperStackItem>):bool
 * @psalm-type ValueMapper = Closure(mixed,list<RecursiveMapperStackItem>):mixed
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class RecursiveMapperVisitor implements RecursiveMapperVisitorInterface
{
    /**
     * @var ?\Closure
     *
     * @psalm-var ?ArrayMapper
     */
    private $arrayMapper;

    /**
     * @var ?\Closure
     *
     * @psalm-var ?ValueMapper
     */
    private $valueMapper;

    /**
     * @var ?ArrayLike
     */
    private $result;

    /**
     * @var array|\ArrayAccess
     */
    private $current;

    public function __construct()
    {
        $this->current = [];
    }

    /**
     * @psalm-param ?ArrayMapper $arrayMapper
     * @psalm-param ?ValueMapper $valueMapper
     */
    public function begin(?\Closure $arrayMapper = null, ?\Closure $valueMapper = null): void
    {
        $this->arrayMapper = $arrayMapper;
        $this->valueMapper = $valueMapper;

        $this->result = null;
        $this->current = [];
    }

    public function end(): void
    {
        $this->current = [];
    }

    /**
     * @return ?ArrayLike
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
        if (null === $this->arrayMapper) {
            if (!is_array($node)) {
                return false;
            }

            $this->current = $node;

            return true;
        }

        $mapped = $node;

        if (!call_user_func_array($this->arrayMapper, [&$mapped, $stack])) {
            return false;
        }

        if (!is_array($mapped) && !($mapped instanceof \ArrayAccess)) {
            // Can't proceed with non-indexable.
            return false;
        }

        $this->current = $mapped;

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

        $this->set($stack, $this->current);
    }

    /**
     * @param mixed $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function visit($node, array $stack, bool $iterating): void
    {
        if (null === $this->valueMapper) {
            /** @psalm-var mixed */
            $mapped = $node;
        } else {
            /** @psalm-var mixed */
            $mapped = call_user_func_array($this->valueMapper, [$node, $stack]);
        }

        $this->set($stack, $mapped);
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
        return new RecursiveMapperStackItem($node, $key, $this->current);
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
     * @param mixed $value
     *
     * @psalm-param list<StackItem> $stack
     */
    private function set(array $stack, $value): void
    {
        $count = count($stack);

        if (0 === $count) {
            if (!is_iterable($value)) {
                $expected = 'an iterable';
                $actual = is_object($value) ? get_class($value) : gettype($value);

                /** @psalm-suppress MissingThrowsDocblock */
                throw InvalidArgumentException::fromBackTrace(2, $expected, $actual);
            }

            /** @psalm-var ArrayLike */
            $this->result = $value;

            return;
        }

        $stack[$count - 1]->set($value);
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
}

// vim: syntax=php sw=4 ts=4 et:
