<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\ReflectionException;
use PHPUnit\Framework\MockObject\RuntimeException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\Constraint\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 *
 * @psalm-template ConstraintClass of Constraint
 * @psalm-template CreateConstraintArgs of array
 *
 * @template-extends TestCase<ConstraintClass, CreateConstraintArgs>
 */
abstract class RecursiveConstraintTestCase extends TestCase
{
    abstract public static function subject(): string;

    abstract public static function selectable(): string;

    abstract public static function adjective(): string;

    /**
     * @psalm-return iterable<string,array{args:array,expect:array{values: Constraint}}>
     *
     * @codeCoverageIgnoreStart
     */
    public static function provCreateConstraint(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'args'   => [['foo' => 'FOO']],
            'expect' => [
                'values' => self::identicalTo(['foo' => 'FOO']),
            ],
        ];
    }

    // @codeCoverageIgnoreEnd
    /**
     * @dataProvider provCreateConstraint
     *
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @psalm-param array                     $args
     * @psalm-param array{values: Constraint} $expect
     */
    final public function testCreateConstraint(array $args, array $expect): void
    {
        $constraint = $this->examineCreateConstraint($args);
    }

    /**
     * @throws Exception
     * @throws ExpectationFailedException
     * @throws ReflectionException
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    final public function testConstraintUnaryOperatorFailure(): void
    {
        $this->examineConstraintUnaryOperatorFailure([[]], null, self::message('null'));

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd
    /**
     * @param mixed $actual
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @psalm-param ArrayLike $expect
     */
    final public function examineValuesMatchSucceeds(iterable $expect, $actual): void
    {
        $this->examineConstraintMatchSucceeds([$expect], $actual);
    }

    /**
     * @param mixed $actual
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws CircularDependencyException
     *
     * @psalm-param ArrayLike $expect
     */
    final public function examineValuesMatchFails(iterable $expect, $actual, string $string): void
    {
        $this->examineConstraintMatchFails([$expect], $actual, self::message($string));

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd
    /**
     * @param mixed $actual
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @psalm-param ArrayLike $expect
     */
    final public function examineNotValuesMatchSucceeds(iterable $expect, $actual): void
    {
        $this->examineNotConstraintMatchSucceeds([$expect], $actual);
    }

    /**
     * @param mixed $actual
     *
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     *
     * @psalm-param ArrayLike $expect
     */
    final public function examineNotValuesMatchFails(iterable $expect, $actual, string $string): void
    {
        $this->examineNotConstraintMatchFails([$expect], $actual, self::message($string, true));

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * Assembles expected failure message out of pieces.
     *
     * @param string $export   A noun representing the actual value, such as
     *                         "123" or "array" or "object stdClass"
     * @param bool   $negative indicates whether the generated message is for the
     *                         tested constraint (false) or a constraint negated
     *                         with LogicalNot (true)
     */
    final protected static function message(string $export, bool $negative = false): string
    {
        return sprintf('Failed asserting that %s.', self::statement($export, $negative));
    }

    /**
     * Assembles a statement which is a part of failure message.
     *
     * @param string $export   A noun representing the actual value,
     *                         such as "123" or "array" or "object
     *                         stdClass"
     * @param bool   $negative indicates whether the generated statement is for
     *                         the constraint under test (false) or a constraint
     *                         negated with LogicalNot (true)
     */
    final protected static function statement(string $export, bool $negative = false): string
    {
        return sprintf(
            '%s %s %s with %s %s specified',
            $export,
            $negative ? 'fails to be' : 'is',
            static::subject(),
            static::selectable(),
            static::adjective()
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
