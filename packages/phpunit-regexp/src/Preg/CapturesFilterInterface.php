<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Preg;

/**
 * @internal This interface is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
interface CapturesFilterInterface
{
    /**
     * Filter-out all elements of $array with $this->accepts().
     *
     * @return array the filtered array
     * @psalm-return array<array-key, string|null|array{0:string|null,1:int}>
     */
    public function filter(array $array): array;

    /**
     * Returns true if $value is a capture group returned by preg_match().
     *
     * The method shall return true only in the following situations:
     *
     *  - $value is a string or PREG_UNMATCHED_AS_NULL is set and $value is null, or
     *  - PREG_OFFSET_CAPTURE is set and
     *
     *      - $value is two-element array, and
     *      - $value[0] is a string or PREG_UNMATCHED_AS_NULL is set and $value is null, and
     *      - $value[1] is an integer,
     *
     * @param mixed $value
     * @psalm-assert-if-true string|null|array{0:string|null,1:int} $value
     */
    public function accepts($value): bool;
}

// vim: syntax=php sw=4 ts=4 et:
