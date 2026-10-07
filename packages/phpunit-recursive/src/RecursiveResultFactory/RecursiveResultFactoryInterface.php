<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
interface RecursiveResultFactoryInterface
{
    /**
     * @param mixed $input
     *
     * @psalm-param ArrayLike $array
     */
    public function supports(iterable $array, $input): bool;

    /**
     * @param mixed $input
     *
     * @return mixed
     *
     * @psalm-param ArrayLike $array
     */
    public function getActualResult(iterable $array, $input);

    /**
     * @return mixed
     *
     * @psalm-param ArrayLike $array
     */
    public function getExpectedResult(iterable $array);
}

// vim: syntax=php sw=4 ts=4 et:
