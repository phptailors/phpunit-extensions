<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
interface SorterInterface
{
    /**
     * @param mixed $subject
     */
    public function supports($subject): bool;

    /**
     * @param mixed $subject
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public function sorted($subject): array;

    /**
     * A short string naming the subject type(s) supported by this selector
     * (e.g. "an array", "an object", "a class", etc.).
     */
    public function subject(): string;

//    /**
//     * A name for the values being sorted from subject (in plural,
//     * e.g. "values" or "properties").
//     */
//    public function sortable(): string;
}

// vim: syntax=php sw=4 ts=4 et:
