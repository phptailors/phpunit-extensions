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
 *
 * @psalm-type StackItem = RecursiveResultUnwrapperStackItem
 *
 * @template-extends RecursiveVisitorInterface<RecursiveResultUnwrapperStackItem>
 */
interface RecursiveResultUnwrapperVisitorInterface extends RecursiveVisitorInterface
{
    public function begin(bool $actual): void;

    public function end(): void;

    /**
     * @return null|array|ResultInterface
     */
    public function result();
}

// vim: syntax=php sw=4 ts=4 et:
