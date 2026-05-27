<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultUnwrapper;

use Tailors\PHPUnit\InternalErrorException;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversal;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversalInterface;
use Tailors\PHPUnit\Result\ResultInterface;

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
    private $recursiveResultUnwrapperVisitor;

    /**
     * @var RecursiveTraversalInterface
     *
     * @psalm-readonly
     */
    private $recursiveTraversal;

    public static function create(bool $actual, bool $tagging = true): self
    {
        $recursiveResultUnwrapperVisitor = new RecursiveResultUnwrapperVisitor($actual, $tagging);
        $recursiveTraversal = new RecursiveTraversal();

        return new self($recursiveResultUnwrapperVisitor, $recursiveTraversal);
    }

    public function __construct(
        RecursiveResultUnwrapperVisitorInterface $recursiveResultUnwrapperVisitor,
        RecursiveTraversalInterface $recursiveTraversal
    ) {
        $this->recursiveResultUnwrapperVisitor = $recursiveResultUnwrapperVisitor;
        $this->recursiveTraversal = $recursiveTraversal;
    }

    /**
     * @param array|\Traversable $array
     *
     * @return array|ResultInterface
     */
    public function unwrap($array)
    {
        $this->recursiveResultUnwrapperVisitor->reset();

        $this->recursiveTraversal->walk($array, $this->recursiveResultUnwrapperVisitor);

        $unwrapped = $this->recursiveResultUnwrapperVisitor->result();

        if (null === $unwrapped) {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace('$this->recursiveResultUnwrapperVisitor->result() returned null');
        }

        return $unwrapped;
    }
}

// vim: syntax=php sw=4 ts=4 et:
