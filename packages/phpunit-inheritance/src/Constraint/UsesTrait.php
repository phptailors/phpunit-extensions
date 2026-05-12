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
 * Constraint that accepts classes that extend given class.
 */
final class UsesTrait extends AbstractConstraint
{
    use ConstraintImplementationTrait;

    private static string $verb = 'uses trait';
    private static string $negatedVerb = 'does not use trait';

    /**
     * @psalm-var array{0:callable, 1:string}
     */
    private static array $validation = ['trait_exists', 'a trait-string'];

    /**
     * @psalm-var callable
     */
    private static mixed $inheritance = 'class_uses';

    /**
     * @psalm-var array{0:callable, 1:callable}
     */
    private static array $supports = ['class_exists', 'trait_exists'];
}

// vim: syntax=php sw=4 ts=4 et:
