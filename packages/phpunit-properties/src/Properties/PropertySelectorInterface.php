<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

/**
 * @internal This interface is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
interface PropertySelectorInterface
{
    /**
     * @param mixed $subject
     */
    public function canSelectFrom($subject): bool;

    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $retval
     *
     * @psalm-param array-key $key
     */
    public function selectProperty($subject, $key, &$retval = null): bool;

    /**
     * Returns short description of subject type supported by this selector.
     */
    public function subject(): string;
}

// vim: syntax=php sw=4 ts=4 et:
