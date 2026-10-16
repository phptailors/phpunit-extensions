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
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorInterface;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type StackItem   = RecursiveMapperStackItem
 * @psalm-type ArrayMapper = Closure(ArrayLike,list<RecursiveMapperStackItem>):bool
 * @psalm-type ValueMapper = Closure(mixed,list<RecursiveMapperStackItem>):mixed
 *
 * @template-extends RecursiveVisitorInterface<RecursiveMapperStackItem>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
interface RecursiveMapperVisitorInterface extends RecursiveVisitorInterface
{
    /**
     * @psalm-param ?ArrayMapper $arrayMapper
     * @psalm-param ?ValueMapper $valueMapper
     */
    public function begin(?\Closure $arrayMapper = null, ?\Closure $valueMapper = null): void;

    public function end(): void;

    /**
     * @return null|ArrayLike
     */
    public function result();
}

// vim: syntax=php sw=4 ts=4 et:
