<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * @internal This interface is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
interface ValueSelectorInterface
{
    /**
     * @param mixed $subject
     */
    public function supports($subject): bool;

    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $retval
     *
     * @psalm-param array-key $key
     * @param-out mixed $retval
     *
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public function select($subject, $key, &$retval = null): bool;

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
