<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Common\AbstractConstraintTestCase;
use Tailors\PHPUnit\RecursiveConstraint\AbstractRecursiveConstraint;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs = list{0: array, 1?: int}
 *
 * @psalm-template ConstraintClass of AbstractRecursiveConstraint
 *
 * @template-extends AbstractConstraintTestCase<ConstraintClass, list{0: array, 1?: int}>
 */
abstract class KsortedConstraintTestCase extends AbstractConstraintTestCase
{
    abstract public static function adjective(): string;

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
     */
    final public static function provConstraintUnaryOperatorFailure(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [[]],
            'actual'   => null,
            'message'  => self::message('null'),
        ];
    }

    /**
     * @codeCoverageIgnore Only called from providers.
     */
    final protected static function message(string $export, bool $negative = false): string
    {
        return sprintf('Failed asserting that %s', self::statement($export, $negative));
    }

    /**
     * @codeCoverageIgnore Only called from providers.
     */
    final protected static function statement(string $export, bool $negative = false): string
    {
        $verb = $negative ? 'fails to be' : 'is';

        return sprintf('%s %s an array %s the specified one when ksorted', $export, $verb, static::adjective());
    }
}

// vim: syntax=php sw=4 ts=4 et:
