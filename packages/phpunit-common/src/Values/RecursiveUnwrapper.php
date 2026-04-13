<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\CircularDependencyException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveUnwrapper implements RecursiveUnwrapperInterface
{
    public const UNIQUE_TAG = RecursiveUnwrapperVisitor::UNIQUE_TAG;

    /**
     * @var bool
     */
    private $tagging;

    /**
     * Initializes the object.
     *
     * @param bool $tagging
     *                      If true, then a unique tag will be appended to the end of every
     *                      array that results from unwrapping of array of properties
     */
    public function __construct(bool $tagging = true)
    {
        $this->tagging = $tagging;
    }

    /**
     * Walk recursively through $values and unwrap nested instances of
     * ValuesInterface when suitable.
     *
     * @throws CircularDependencyException
     */
    public function unwrap(ValuesInterface $values): array
    {
        $traversal = new RecursiveTraversal();
        $visitor = new RecursiveUnwrapperVisitor($this->tagging);
        $traversal->walk($values, $visitor);

        return $visitor->result();
    }
}

// vim: syntax=php sw=4 ts=4 et:
