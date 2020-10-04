<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Tailors\PHPUnit\Properties;

/**
 * @internal This interface is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
interface RecursivePropertiesSelectorInterface
{
    /**
     * @param mixed $subject
     */
    public function selectProperties($subject): ActualPropertiesInterface;
}

// vim: syntax=php sw=4 ts=4 et:
