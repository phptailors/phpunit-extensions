<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultUnwrapper;

use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
interface RecursiveResultUnwrapperInterface
{
    /**
     * @return array|ResultInterface
     *
     * @psalm-param ArrayLike $array
     */
    public function unwrap(bool $actual, iterable $array);
}

// vim: syntax=php sw=4 ts=4 et:
