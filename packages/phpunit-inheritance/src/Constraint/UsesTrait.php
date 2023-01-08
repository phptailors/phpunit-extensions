<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Inheritance\AbstractConstraint;
use Tailors\PHPUnit\Inheritance\ConstraintImplementationTrait;

/**
 * Constraint that accepts classes that extend given class.
 */
final class UsesTrait extends AbstractConstraint
{
    use ConstraintImplementationTrait;

    /**
     * @var string
     */
    private static $verb = 'uses trait';

    /**
     * @var string
     */
    private static $negatedVerb = 'does not use trait';

    /**
     * @var array
     *
     * @psalm-var array{0:callable, 1:string}
     */
    private static $validation = ['trait_exists', 'a trait-string'];

    /**
     * @var callable
     *
     * @psalm-var callable
     */
    private static $inheritance = 'class_uses';

    /**
     * @var array
     *
     * @psalm-var array{0:callable, 1:callable}
     */
    private static $supports = ['class_exists', 'trait_exists'];
}

// vim: syntax=php sw=4 ts=4 et:
