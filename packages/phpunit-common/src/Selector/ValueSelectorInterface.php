<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Selector;

use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
interface ValueSelectorInterface
{
    public function supports(mixed $subject): bool;

    /**
     * @param-out mixed $retval
     *
     * @throws InvalidArgumentException
     *
     * @psalm-param array-key $key
     */
    public function select(mixed $subject, mixed $key, mixed &$retval): bool;

    /**
     * A short string naming the subject type(s) supported by this selector
     * (e.g. "an array", "an object", "a class", etc.).
     */
    public function subject(): string;

    /**
     * A name for the values being selected from subject (in plural,
     * e.g. "values" or "properties").
     */
    public function selectable(): string;
}

// vim: syntax=php sw=4 ts=4 et:
