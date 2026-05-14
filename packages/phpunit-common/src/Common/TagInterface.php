<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

/**
 * An array of actual or expected values.
 *
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
interface TagInterface
{
    /**
     * @psalm-return non-empty-string
     */
    public function tag(): string;
}

// vim: syntax=php sw=4 ts=4 et:
