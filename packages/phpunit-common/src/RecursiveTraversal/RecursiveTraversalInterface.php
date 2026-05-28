<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveTraversal;

use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorInterface;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorStackItemInterface;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
interface RecursiveTraversalInterface
{
    /**
     * @psalm-template StackItem of RecursiveVisitorStackItemInterface
     *
     * @psalm-param ArrayLike $array
     * @psalm-param RecursiveVisitorInterface<StackItem> $visitor
     */
    public function walk(iterable $array, RecursiveVisitorInterface $visitor): void;
}

// vim: syntax=php sw=4 ts=4 et:
