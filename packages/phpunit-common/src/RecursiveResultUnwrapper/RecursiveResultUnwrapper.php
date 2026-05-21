<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultUnwrapper;

use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversalInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveResultUnwrapper implements RecursiveResultUnwrapperInterface
{
    /**
     * @var RecursiveResultUnwrapperVisitor
     *
     * @psalm-readonly
     */
    private $recursiveVisitor;

    /**
     * @var RecursiveTraversalInterface
     *
     * @psalm-readonly
     */
    private $recursiveTraversal;

    public function __construct(
        RecursiveResultUnwrapperVisitorInterface $recursiveVisitor,
        RecursiveTraversalInterface $recursiveTraversal
    ) {
        $this->recursiveVisitor = $recursiveVisitor;
        $this->recursiveTraversal = $recursiveTraversal;
    }

    /**
     * @param array|\Traversable $array
     */
    public function unwrap($array): array
    {
        $this->recursiveVisitor->reset();

        $this->recursiveTraversal->walk($array, $this->recursiveVisitor);

        return $this->recursiveVisitor->result();
    }
}

// vim: syntax=php sw=4 ts=4 et:
