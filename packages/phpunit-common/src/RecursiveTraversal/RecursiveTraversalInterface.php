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

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
interface RecursiveTraversalInterface
{
    /**
     * @param array|\Traversable $values
     *
     * @psalm-template StackItem of RecursiveVisitorStackItemInterface
     *
     * @psalm-param RecursiveVisitorInterface<StackItem> $visitor
     */
    public function walk($values, RecursiveVisitorInterface $visitor): void;
}

// vim: syntax=php sw=4 ts=4 et:
