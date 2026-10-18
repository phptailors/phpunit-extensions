<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorStackItemInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class RecursiveExpectedResultFactoryStackItem implements RecursiveVisitorStackItemInterface
{
    /**
     * @param mixed              $key
     * @param array|\ArrayAccess $result
     *
     * @psalm-param ArrayLike $node
     * @psalm-param array-key $key
     */
    public function __construct(
        private readonly iterable $node,
        private $key,
        private $result
    ) {}

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
     * @psalm-return array-key
     *
     * @psalm-mutation-free
     */
    public function key(): mixed
    {
        return $this->key;
    }

    public function set(mixed $value): void
    {
        $this->result[$this->key] = $value;
    }

    public function result(): array|\ArrayAccess
    {
        return $this->result;
    }
}

// vim: syntax=php sw=4 ts=4 et:
