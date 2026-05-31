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
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
final class RecursiveResultUnwrapper implements RecursiveResultUnwrapperInterface
{
    /**
     * @var RecursiveResultUnwrapperVisitorInterface
     *
     * @psalm-readonly
     */
    private $visitor;

    /**
     * @var RecursiveTraversalInterface
     *
     * @psalm-readonly
     */
    private $traversal;

    public static function create(bool $tagging = true): self
    {
        $visitor = new RecursiveResultUnwrapperVisitor($tagging);
        $traversal = new RecursiveTraversal();

        return new self($visitor, $traversal);
    }

    public function __construct(
        RecursiveResultUnwrapperVisitorInterface $visitor,
        RecursiveTraversalInterface $traversal
    ) {
        $this->visitor = $visitor;
        $this->traversal = $traversal;
    }

    /**
     * @return array|ResultInterface
     *
     * @psalm-param ArrayLike $array
     */
    public function unwrap(bool $actual, iterable $array)
    {
        $this->visitor->begin($actual);

        $this->traversal->walk($array, $this->visitor);

        $this->visitor->end();

        $unwrapped = $this->visitor->result();

        if (null === $unwrapped) {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace('$this->visitor->result() returned null');
        }

        return $unwrapped;
    }
}

// vim: syntax=php sw=4 ts=4 et:
