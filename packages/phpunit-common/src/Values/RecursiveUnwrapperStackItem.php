<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveUnwrapperStackItem implements RecursiveVisitorStackItemInterface
{
    /**
     * @psalm-param array-key $key
     */
    public function __construct(private readonly array|ValuesInterface $node, private readonly mixed $key, private array $result) {}

    /**
     * @psalm-mutation-free
     */
    public function node(): array|ValuesInterface
    {
        return $this->node;
    }

    /**
     * @psalm-return array-key
     *
     * @psalm-mutation-free
     */
    public function key(): mixed
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
        $this->result[$this->key()] = $value;
    }
}

// vim: syntax=php sw=4 ts=4 et:
