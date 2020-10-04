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
 * @extends \IteratorAggregate<array-key, mixed>
 *
 * @internal This interface is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
interface PropertiesInterface extends \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @return array
     */
    public function getArrayCopy();

    public function canUnwrapChild(PropertiesInterface $child): bool;
}

// vim: syntax=php sw=4 ts=4 et:
