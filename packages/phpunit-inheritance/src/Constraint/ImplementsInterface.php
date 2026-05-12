<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Inheritance\AbstractConstraint;
use Tailors\PHPUnit\Inheritance\ConstraintImplementationTrait;

/**
 * Constraint that accepts classes that implement given interface.
 */
final class ImplementsInterface extends AbstractConstraint
{
    use ConstraintImplementationTrait;

    private static string $verb = 'implements interface';
    private static string $negatedVerb = 'does not implement interface';

    /**
     * @psalm-var array{0:callable, 1:string}
     */
    private static array $validation = ['interface_exists', 'an interface-string'];

    /**
     * @psalm-var callable
     */
    private static mixed $inheritance = 'class_implements';

    /**
     * @psalm-var array{0:callable, 1:callable}
     */
    private static array $supports = ['class_exists', 'interface_exists'];
}

// vim: syntax=php sw=4 ts=4 et:
