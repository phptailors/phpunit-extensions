<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
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
    public function getResult(bool $actual, iterable $array, $input);
}

// vim: syntax=php sw=4 ts=4 et:
