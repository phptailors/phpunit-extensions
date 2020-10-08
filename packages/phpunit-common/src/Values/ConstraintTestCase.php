<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Constraint\UnaryOperator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
abstract class ConstraintTestCase extends TestCase
{
    abstract public static function subject(): string;

    abstract public static function selectable(): string;

    abstract public static function adjective(): string;

    /**
     * @psalm-return class-string
     */
    abstract public static function comparatorClass(): string;

    /**
     * @psalm-return class-string
     */
    abstract public static function constraintClass(): string;

    /**
     * @param mixed $args
     */
    abstract public static function createConstraint(...$args): AbstractConstraint;

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testExtendsAbstractConstraint(): void
    {
        self::assertInstanceOf(AbstractConstraint::class, static::createConstraint([]));
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testImplementsSelectionAggregateInterface(): void
    {
        self::assertInstanceOf(SelectionAggregateInterface::class, static::createConstraint([]));
    }

    // @codeCoverageIgnoreStart
    public function provCreate(): array
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
     * @dataProvider provCreate
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     *
     * @psalm-param array{values:\PHPUnit\Framework\Constraint\Constraint} $expect
     */
    public function testCreate(array $args, array $expect): void
    {
        $constraint = static::createConstraint(...$args);
        self::assertThat($constraint->getSelection()->getArrayCopy(), $expect['values']);
    }

    /**
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    final public function testFailureExceptionInUnaryOperatorContext(): void
    {
        $constraint = static::createConstraint([]);

        $unary = $this->wrapWithUnaryOperator($constraint);
        $verbSubject = sprintf('is %s', static::subject());
        $selectableAdjective = sprintf('%s %s', static::selectable(), static::adjective());

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage(self::message('null', $verbSubject, $selectableAdjective));

        self::assertThat(null, $unary);

        // @codeCoverageIgnoreStart
    }

    /**
     * @param mixed $actual
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    final public function examineValuesMatchSucceeds(array $expect, $actual): void
    {
        $constraint = static::createConstraint($expect);
        self::assertThat($actual, $constraint);
    }

    /**
     * @param mixed $actual
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Tailors\PHPUnit\CircularDependencyException
     */
    final public function examineValuesMatchFails(array $expect, $actual, string $string): void
    {
        $constraint = static::createConstraint($expect);
        $verbSubject = sprintf('is %s', static::subject());
        $selectableAdjective = sprintf('%s %s', static::selectable(), static::adjective());
        $message = self::message($string, $verbSubject, $selectableAdjective);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $constraint->evaluate($actual);
        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @param mixed $actual
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    final public function examineNotValuesMatchSucceeds(array $expect, $actual): void
    {
        $constraint = self::logicalNot(static::createConstraint($expect));
        self::assertThat($actual, $constraint);
    }

    /**
     * @param mixed $actual
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    final public function examineNotValuesMatchFails(array $expect, $actual, string $string): void
    {
        $constraint = self::logicalNot(static::createConstraint($expect));
        $verbSubject = sprintf('fails to be %s', static::subject());
        $selectableAdjective = sprintf('%s %s', static::selectable(), static::adjective());
        $message = self::message($string, $verbSubject, $selectableAdjective);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $constraint->evaluate($actual);
        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * Returns $constraint wrapped with UnaryOperator mock.
     *
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    final protected function wrapWithUnaryOperator(
        AbstractConstraint $constraint,
        string $operator = 'noop',
        int $precedence = 1
    ): UnaryOperator {
        $unary = $this->getMockBuilder(UnaryOperator::class)
            ->setConstructorArgs([$constraint])
            ->getMockForAbstractClass()
        ;

        $unary->expects(self::any())
            ->method('operator')
            ->willReturn($operator)
        ;
        $unary->expects(self::any())
            ->method('precedence')
            ->willReturn($precedence)
        ;

        return $unary;
    }

    // @codeCoverageIgnoreEnd

    /**
     * Assembles expected failure message out of pieces.
     *
     * @param string $value               A noun representing the actual value,
     *                                    such as "123" or "array" or "object
     *                                    stdClass"
     * @param string $verbSubject         A concatenated verb and subject, such
     *                                    as "is a class", or "fails to be an
     *                                    object"
     * @param string $adjective           An adjective reflecting the
     *                                    comparison: "equal to" or "identical
     *                                    to"
     * @param string $selectableAdjective A selectable name and adjective
     *                                    reflecting the comparison: "values
     *                                    equal to" or "properties identical
     *                                    to"
     */
    final protected static function message(string $value, string $verbSubject, string $selectableAdjective): string
    {
        return sprintf('Failed asserting that %s.', self::statement($value, $verbSubject, $selectableAdjective));
    }

    /**
     * Assembles a statement which is a part of failure message.
     *
     * @param string $value               A noun representing the actual value,
     *                                    such as "123" or "array" or "object
     *                                    stdClass"
     * @param string $verbSubject         A concatenated verb and subject, such
     *                                    as "is a class", or "fails to be an
     *                                    object"
     * @param string $selectableAdjective A selectable name and adjective
     *                                    reflecting the comparison: "values
     *                                    equal to" or "properties identical
     *                                    to"
     */
    final protected static function statement(string $value, string $verbSubject, string $selectableAdjective): string
    {
        return sprintf('%s %s with %s specified', $value, $verbSubject, $selectableAdjective);
    }
}

// vim: syntax=php sw=4 ts=4 et:
