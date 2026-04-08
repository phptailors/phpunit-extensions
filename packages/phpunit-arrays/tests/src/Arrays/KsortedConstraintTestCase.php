<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Constraint\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
abstract class KsortedConstraintTestCase extends TestCase
{
    abstract public static function adjective(): string;

    abstract public static function getComparatorClass(): string;

    final public function testCreateConstraint(): void
    {
        $constraint = $this->examineCreateConstraint([[]]);
        $this->assertInstanceOf(static::getComparatorClass(), $constraint->getComparator());
    }

    final public function testConstraintUnaryOperatorFailure(): void
    {
        $this->examineConstraintUnaryOperatorFailure([[]], null, self::message('null'));

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    final protected static function message(string $export, bool $negative = false): string
    {
        return sprintf('Failed asserting that %s', self::statement($export, $negative));
    }

    final protected static function statement(string $export, bool $negative = false): string
    {
        $verb = $negative ? 'fails to be' : 'is';

        return sprintf('%s %s an array %s specified one when ksorted', $export, $verb, static::adjective());
    }
}

// vim: syntax=php sw=4 ts=4 et:
