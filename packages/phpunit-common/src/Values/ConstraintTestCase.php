<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\Constraint\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @extends TestCase<AbstractConstraint>
 */
abstract class ConstraintTestCase extends TestCase
{
    abstract public static function subject(): string;

    abstract public static function selectable(): string;

    abstract public static function adjective(): string;

    /**
     * @psalm-return array<string,array{args:array,expect:array{values: Constraint}}>
     *
     * @codeCoverageIgnoreStart
     */
    public function provCreateConstraint(): array
    {
        return [
            'ConstraintTestCase.php:'.__LINE__ => [
                'args'   => [['foo' => 'FOO']],
                'expect' => [
                    'values' => self::identicalTo(['foo' => 'FOO']),
                ],
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provCreateConstraint
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @psalm-param array{values:\PHPUnit\Framework\Constraint\Constraint} $expect
     */
    final public function testCreateConstraint(array $args, array $expect): void
    {
        $constraint = $this->examineCreateConstraint($args);
        self::assertThat($constraint->getSelection()->getArrayCopy(), $expect['values']);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\ReflectionException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    final public function testConstraintUnaryOperatorFailure(): void
    {
        $this->examineConstraintUnaryOperatorFailure([[]], null, self::message('null'));

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @param array $expect
     * @param mixed $actual
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    final public function examineValuesMatchSucceeds(array $expect, $actual): void
    {
        $this->examineConstraintMatchSucceeds([$expect], $actual);
    }

    /**
     * @param array  $expect
     * @param mixed  $actual
     * @param string $string
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Tailors\PHPUnit\CircularDependencyException
     */
    final public function examineValuesMatchFails(array $expect, $actual, string $string): void
    {
        $this->examineConstraintMatchFails([$expect], $actual, self::message($string));

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @param array $expect
     * @param mixed $actual
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    final public function examineNotValuesMatchSucceeds(array $expect, $actual): void
    {
        $this->examineNotConstraintMatchSucceeds([$expect], $actual);
    }

    /**
     * @param array  $expect
     * @param mixed  $actual
     * @param string $string
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    final public function examineNotValuesMatchFails(array $expect, $actual, string $string): void
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
