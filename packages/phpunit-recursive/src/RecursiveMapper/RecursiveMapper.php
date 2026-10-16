<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveMapper;

use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\InternalErrorException;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversal;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversalInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type ArrayMapper = Closure(ArrayLike,list<RecursiveMapperStackItem>):bool
 * @psalm-type ValueMapper = Closure(mixed,list<RecursiveMapperStackItem>):mixed
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class RecursiveMapper implements RecursiveMapperInterface
{
    /**
     * @var RecursiveMapperVisitorInterface
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

    public function __construct(RecursiveMapperVisitorInterface $visitor, RecursiveTraversalInterface $traversal)
    {
        $this->visitor = $visitor;
        $this->traversal = $traversal;
    }

    public static function create(): self
    {
        $visitor = new RecursiveMapperVisitor();
        $traversal = new RecursiveTraversal();

        return new self($visitor, $traversal);
    }

    /**
     * @psalm-param ArrayLike    $array
     * @psalm-param ?ArrayMapper $arrayMapper
     * @psalm-param ?ValueMapper $valueMapper
     *
     * @psalm-return ArrayLike
     */
    public function map(iterable $array, ?\Closure $arrayMapper = null, ?\Closure $valueMapper = null): iterable
    {
        $this->visitor->begin($arrayMapper, $valueMapper);

        $this->traversal->walk($array, $this->visitor);

        $this->visitor->end();

        $mapped = $this->visitor->result();

        if (null === $mapped) {
            /** @psalm-suppress MissingThrowsDocblock */
            throw InternalErrorException::fromBackTrace('$this->visitor->result() returned null');
        }

        return $mapped;
    }
}

// vim: syntax=php sw=4 ts=4 et:
