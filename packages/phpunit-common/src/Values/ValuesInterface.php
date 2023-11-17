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
 * An array of actual or expected values.
 *
 * @template-extends \Traversable<array-key, mixed>
 * @template-extends \ArrayAccess<array-key, mixed>
 *
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
interface ValuesInterface extends \Traversable, \ArrayAccess, \Countable
{
    /**
     * @return array
     */
    public function getArrayCopy();

    /**
     * Returns true if this object represents actual values (as opposite to expected values).
     */
    public function actual(): bool;
}

// vim: syntax=php sw=4 ts=4 et:
