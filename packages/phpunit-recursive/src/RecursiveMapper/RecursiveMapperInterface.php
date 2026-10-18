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

/**
 * @internal This interface is not covered by the backward compatibility promise
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
interface RecursiveMapperInterface
{
    /**
     * @psalm-param ArrayLike    $array
     * @psalm-param ?ArrayMapper $arrayMapper
     * @psalm-param ?ValueMapper $valueMapper
     *
     * @psalm-return ArrayLike
     */
    public function map(iterable $array, ?\Closure $arrayMapper = null, ?\Closure $valueMapper = null): iterable;
}

// vim: syntax=php sw=4 ts=4 et:
