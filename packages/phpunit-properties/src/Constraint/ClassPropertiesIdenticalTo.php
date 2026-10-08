<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\Comparator\IdentityComparator;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Properties\ClassPropertiesSelection;
use Tailors\PHPUnit\Properties\ValidateExpectationsTrait;
use Tailors\PHPUnit\RecursiveConstraint\AbstractRecursiveConstraint;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactory;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapper;

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class ClassPropertiesIdenticalTo extends AbstractRecursiveConstraint
{
    use ValidateExpectationsTrait;

    /**
     * @psalm-assert iterable<string, mixed> $expectations
     *
     * @throws InvalidArgumentException
     *
     * @psalm-param ArrayLike $expectations
     */
    public static function create(iterable $expectations): self
    {
        self::validateExpectations($expectations, 1);

        return new self(
            new ClassPropertiesSelection($expectations),
            new IdentityComparator(),
            RecursiveResultFactory::create(),
            RecursiveResultUnwrapper::create()
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
