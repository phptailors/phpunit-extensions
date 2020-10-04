<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Tailors\PHPUnit\Examples\Inheritance;

/**
 * Example trait for testing purposes.
 *
 * @internal This trait is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
trait ExampleTraitUsingTrait
{
    use ExampleTrait;
}

// vim: syntax=php sw=4 ts=4 et: