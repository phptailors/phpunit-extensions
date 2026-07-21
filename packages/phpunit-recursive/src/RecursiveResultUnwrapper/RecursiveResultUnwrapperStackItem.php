<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultUnwrapper;

use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorStackItemInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveResultUnwrapperStackItem implements RecursiveVisitorStackItemInterface
{
    /**
     * @var iterable
     *
     * @psalm-var iterable<array-key, mixed>
     *
     * @psalm-readonly
     */
    private $node;

    /**
     * @var mixed
     *
     * @psalm-var array-key
     *
     * @psalm-readonly
     */
    private $key;

    /**
     * @var array
     *
     * @psalm-var array<array-key, mixed>
     */
    private $result;

    /**
     * @param mixed $key
     *
     * @psalm-param iterable<array-key, mixed> $node
     * @psalm-param array-key                  $key
     * @psalm-param array<array-key, mixed>    $result
     */
    public function __construct(iterable $node, $key, array $result)
    {
        $this->node = $node;
        $this->key = $key;
        $this->result = $result;
    }

    /**
     * @psalm-return iterable<array-key, mixed>
     *
     * @psalm-mutation-free
     */
    public function node(): iterable
    {
        return $this->node;
    }

    /**
     * @return mixed
     *
     * @psalm-return array-key
     *
     * @psalm-mutation-free
     */
    public function key()
    {
        return $this->key;
    }

    public function result(): array
    {
        return $this->result;
    }

    /**
     * @param mixed $value
     */
    public function set($value): void
    {
        $this->result[$this->key] = $value;
    }
}

// vim: syntax=php sw=4 ts=4 et:
