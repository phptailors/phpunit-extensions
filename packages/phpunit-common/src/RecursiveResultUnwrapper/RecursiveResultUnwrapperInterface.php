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


/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
interface RecursiveResultUnwrapperInterface
{
    /**
     * @param array|\Traversable $array
     *
     * @psalm-return array
     */
    public function unwrapExpectedResult($array): array;

    /**
     * @param array|\Traversable $array
     *
     * @psalm-return array
     */
    public function unwrapActualResult($array): array;
}

// vim: syntax=php sw=4 ts=4 et:
