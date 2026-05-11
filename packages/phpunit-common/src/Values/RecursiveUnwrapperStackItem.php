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
     * @param array|ValuesInterface $node
     * @param array-key             $key
     */
    public function __construct(private readonly array|ValuesInterface $node, private readonly mixed $key) {}

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
