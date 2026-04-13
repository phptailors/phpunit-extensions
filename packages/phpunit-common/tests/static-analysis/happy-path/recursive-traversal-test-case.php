<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\RecursiveTraversalTestCase;

use Tailors\PHPUnit\Values\RecursiveTraversal;
use Tailors\PHPUnit\Values\RecursiveVisitorInterface;
use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit\StaticAnalysis\HappyPath\RecursiveTraversalTestCase
 */
final class DummyRecursiveVisitor implements RecursiveVisitorInterface
{
    /**
     * @param array|ValuesInterface $node
     * @param list<array-key>       $path
     */
    public function enter($node, array $path): bool
    {
        return true;
    }

    /**
     * @param array|ValuesInterface $node
     * @param list<array-key>       $path
     */
    public function leave($node, array $path): void {}

    /**
     * @param mixed           $node
     * @param list<array-key> $path
     */
    public function visit($node, array $path): void {}

    /**
     * @param array|ValuesInterface $node
     * @param list<array-key>       $path
     */
    public function cycle($node, array $path): bool
    {
        return false;
    }
}

/**
 * @extends \ArrayObject<array-key,mixed>
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit\StaticAnalysis\HappyPath\RecursiveTraversalTestCase
 */
final class DummyValues extends \ArrayObject implements ValuesInterface
{
    public function actual(): bool
    {
        return false;
    }
}

function consume(): RecursiveTraversal
{
    $traversal = new RecursiveTraversal();
    $visitor = new DummyRecursiveVisitor();
    $values = new DummyValues();

    $traversal->walk($values, $visitor);

    return $traversal;
}

// vim: syntax=php sw=4 ts=4 et:
