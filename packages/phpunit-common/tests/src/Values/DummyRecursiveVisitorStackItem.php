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
     * @param mixed                 $key
     *
     * @psalm-param array-key             $key
     */
    public function __construct($node, $key)
    {
        $this->node = $node;
        $this->key = $key;
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
}

// vim: syntax=php sw=4 ts=4 et:
