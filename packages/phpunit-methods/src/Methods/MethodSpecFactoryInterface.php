<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Methods;

/**
 * @internal This interface is not covered by the backward compatibility promise
 */
interface MethodSpecFactoryInterface
{
    /**
     * Parses string containing method requirement specification.
     *
     * @return MethodSpecInterface method specification created from string or null on error
     *
     * @throws MethodSpecSyntaxError
     */
    public function fromString(string $string): MethodSpecInterface;
}

// vim: syntax=php sw=4 ts=4 et:
