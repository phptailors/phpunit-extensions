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
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class ActualProperties extends \ArrayObject implements ActualPropertiesInterface
{
    /**
     * @psalm-mutation-free
     */
    public function canUnwrapChild(PropertiesInterface $child): bool
    {
        return $child instanceof ActualPropertiesInterface;
    }
}

// vim: syntax=php sw=4 ts=4 et:
