<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultUnwrapper;

use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorInterface;
use Tailors\PHPUnit\Result\ResultInterface;


/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
interface RecursiveResultUnwrapperVisitorInterface extends RecursiveVisitorInterface
{
    /**
     * @return array|null|ResultInterface
     */
    public function result();

    public function reset(): void;
}

// vim: syntax=php sw=4 ts=4 et:
