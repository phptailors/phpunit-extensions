<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Tailors\PHPUnit\Inheritance;

/**
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class FaultyConstraintImplementation extends AbstractConstraint
{
    use ConstraintImplementationTrait {
        inheritance as public;
    }

    /**
     * @var string
     */
    private static $verb = 'is string';

    /**
     * @var string
     */
    private static $negatedVerb = 'is not a string';

    /**
     * @var array
     * @psalm-var array{0:callable, 1:string}
     */
    private static $validation = ['is_string', 'a string'];

    /**
     * @var callable
     * @psalm-var callable
     */
    private static $inheritance = 'strlen';

    /**
     * @var array
     * @psalm-var array{0:callable}
     */
    private static $supports = ['is_string'];
}

// vim: syntax=php sw=4 ts=4 et:
