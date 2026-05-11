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
     * @var array
     */
    private $result;

    /**
     * @param array|ValuesInterface $node
     * @param array-key             $key
     * @param array                 $result
     */
    public function __construct($node, $key, array $result)
    {
        $this->node = $node;
        $this->key = $key;
        $this->result = $result;
    }

    /**
     * @return array|ValuesInterface
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
