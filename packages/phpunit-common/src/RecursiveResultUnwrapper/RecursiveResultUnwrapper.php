<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultUnwrapper;

use Tailors\PHPUnit\ArrayResult\ArrayResultInterface;
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
    private $expectedResultUnwrapperVisitor;

    /**
     * @var RecursiveResultUnwrapperVisitor
     *
     * @psalm-readonly
     */
    private $actualResultUnwrapperVisitor;

    /**
     * @var RecursiveTraversalInterface
     *
     * @psalm-readonly
     */
    private $recursiveTraversal;

    public function __construct(
        RecursiveResultUnwrapperVisitorInterface $expectedResultUnwrapperVisitor,
        RecursiveResultUnwrapperVisitorInterface $actualResultUnwrapperVisitor,
        RecursiveTraversalInterface $recursiveTraversal
    ) {
        $this->expectedResultUnwrapperVisitor = $expectedResultUnwrapperVisitor;
        $this->actualResultUnwrapperVisitor = $actualResultUnwrapperVisitor;
        $this->recursiveTraversal = $recursiveTraversal;
    }

    /**
     * @param array|\Traversable $array
     *
     * @psalm-return array
     */
    public function unwrapExpectedResult($array): array
    {
        return $this->unwrap($array, $this->expectedResultUnwrapperVisitor);
    }

    /**
     * @param array|\Traversable $array
     *
     * @psalm-return array
     */
    public function unwrapActualResult($array): array
    {
        return $this->unwrap($array, $this->actualResultUnwrapperVisitor);
    }

    /**
     * @param array|\Traversable $array
     *
     * @psalm-return array
     */
    private function unwrap($array, RecursiveResultUnwrapperVisitorInterface $recursiveResultUnwrapperVisitor): array
    {
        $recursiveResultUnwrapperVisitor->reset();

        $this->recursiveTraversal->walk($array, $recursiveResultUnwrapperVisitor);

        $unwrapped = $recursiveResultUnwrapperVisitor->result();

        return $unwrapped;
    }
}

// vim: syntax=php sw=4 ts=4 et:
