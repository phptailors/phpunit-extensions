<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorInterface;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type StackItem = RecursiveExpectedResultFactoryStackItem
 *
 * @template-extends RecursiveVisitorInterface<StackItem>
 */
interface RecursiveExpectedResultFactoryVisitorInterface extends RecursiveVisitorInterface
{
    public function begin(): void;

    public function end(): void;

    /**
     * @return mixed
     */
    public function result();
}

// vim: syntax=php sw=4 ts=4 et:
