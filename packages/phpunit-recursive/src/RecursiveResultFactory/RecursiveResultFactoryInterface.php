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
 *
 * @psalm-template SupportedInput
 */
interface RecursiveResultFactoryInterface
{
    /**
     * @param mixed $input
     *
     * @psalm-param ArrayLike $array
     *
     * @psalm-assert-if-true SupportedInput $input
     */
    public function supports(iterable $array, $input): bool;

    /**
     * @param mixed $input
     *
     * @return mixed
     *
     * @psalm-param ArrayLike      $array
     * @psalm-param SupportedInput $input
     */
    public function getActualResult(iterable $array, $input);

    /**
     * @return mixed
     *
     * @psalm-param ArrayLike $array
     *
     * @psalm-return ArrayLike
     */
    public function getExpectedResult(iterable $array): iterable;
}

// vim: syntax=php sw=4 ts=4 et:
