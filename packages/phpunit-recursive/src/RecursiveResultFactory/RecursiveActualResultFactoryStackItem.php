<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorStackItemInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from \Tailors\PHPUnit\Common\TypesInterface
 */
final class RecursiveActualResultFactoryStackItem implements RecursiveVisitorStackItemInterface
{
    /**
     * @var iterable
     *
     * @psalm-var ArrayLike
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
     * @var SubjectResultCouple
     *
     * @psalm-readonly
     */
    private $current;

    /**
     * @param mixed $key
     *
     * @psalm-param ArrayLike $node
     * @psalm-param array-key $key
     */
    public function __construct(iterable $node, $key, SubjectResultCouple $current)
    {
        $this->node = $node;
        $this->key = $key;
        $this->current = $current;
    }

    /**
     * @psalm-return ArrayLike
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

    /**
     * @param mixed $value
     */
    public function set($value): void
    {
        $this->current->result[$this->key] = $value;
    }

    public function current(): SubjectResultCouple
    {
        return $this->current;
    }
}

// vim: syntax=php sw=4 ts=4 et:
