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
 * Specifies requirements on method including its name and modifiers.
 *
 * @internal This interface is not covered by the backward compatibility promise
 */
interface MethodSpecInterface
{
    /**
     * Returns the name of the method being specified.
     *
     * @psalm-return non-empty-string
     */
    public function getName(): string;

    /**
     * Returns specification of ``static`` modifier.
     *
     * @return ?bool ``true`` to match only static methods, ``false`` for non-static, ``null`` for any
     */
    public function getStatic(): ?bool;

    /**
     * Returns specification of methods visibility.
     *
     * @return ?int
     *              a bit combination of ``\ReflectionMethod::IS_PUBLIC``, ``\ReflectionMethod::IS_PROTECTED``
     *              and ``\ReflectionMethod::IS_PRIVATE`` specifying which of these visibility modifiers match
     *              the specification, or ``null`` if anything is accepted
     */
    public function getAccess(): ?int;

    /**
     * Returns specification of ``abstract`` modifier.
     *
     * @return ?bool ``true`` to match only abstract methods, ``false`` for non-abstract, ``null`` for any
     */
    public function getAbstract(): ?bool;

    /**
     * Returns specification of ``final`` modifier.
     *
     * @return ?bool ``true`` to match only final methods, ``false`` for non-final, ``null`` for any
     */
    public function getFinal(): ?bool;

    /**
     * Returns true, if *$method* fulfills all requirements.
     */
    public function matches(\ReflectionMethod $method): bool;

    /**
     * Method specification in human readable form.
     */
    public function toString(): string;
}

// vim: syntax=php sw=4 ts=4 et:
