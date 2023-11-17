<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * An array of expected values.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
class ExpectedValues extends AbstractValues
{
    /**
     * @psalm-mutation-free
     */
    final public function actual(): bool
    {
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
