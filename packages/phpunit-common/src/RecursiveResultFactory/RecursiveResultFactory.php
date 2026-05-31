<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

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
final class RecursiveResultFactory implements RecursiveResultFactoryInterface
{
    /**
     * @var RecursiveResultFactoryVisitorInterface
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

    public function __construct(RecursiveResultFactoryVisitorInterface $visitor, RecursiveTraversalInterface $traversal)
    {
        $this->visitor = $visitor;
        $this->traversal = $traversal;
    }

    public static function create(): self
    {
        $visitor = new RecursiveResultFactoryVisitor();
        $traversal = new RecursiveTraversal();

        return new self($visitor, $traversal);
    }

    /**
     * @param mixed $input
     *
     * @return mixed
     *
     * @psalm-param ArrayLike $array
     */
    public function getResult(bool $actual, iterable $array, $input)
    {
        $this->visitor->begin($actual, $input);

        $this->traversal->walk($array, $this->visitor);

        $this->visitor->end();

        $result = $this->visitor->result();

//        if (null === $result) {
//            /** @psalm-suppress MissingThrowsDocblock */
//            throw InternalErrorException::fromBackTrace('$this->visitor->result() returned null');
//        }

        return $result;
    }
}

// vim: syntax=php sw=4 ts=4 et:
