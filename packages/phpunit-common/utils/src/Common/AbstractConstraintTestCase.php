<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\UnaryOperator;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template ConstraintClass of Constraint
 * @psalm-template CtorArgs of list
 */
abstract class AbstractConstraintTestCase extends TestCase
{
    /**
     * Creates constraint's instance.
     *
     * @psalm-template TArgs of CtorArgs
     *
     * @psalm-param TArgs $ctorArgs
     */
    abstract public static function createConstraint(array $ctorArgs): Constraint;

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed,
     *      message: string
     * }>
     */
    abstract public static function provConstraintUnaryOperatorFailure(): iterable;

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed
     * }>
     */
    abstract public static function provConstraintEvaluationSucceeds(): iterable;

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed,
     *      message: string
     * }>
     */
    abstract public static function provConstraintEvaluationFails(): iterable;

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed
     * }>
     */
    abstract public static function provNotConstraintEvaluationSucceeds(): iterable;

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed,
     *      message: string
     * }>
     */
    abstract public static function provNotConstraintEvaluationFails(): iterable;

    /**
     * @dataProvider provConstraintUnaryOperatorFailure
     *
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testConstraintUnaryOperatorFailure(array $ctorArgs, mixed $actual, string $message): void
    {
        $constraint = static::createConstraint($ctorArgs);

        $unary = $this->wrapWithUnaryOperator($constraint);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        self::assertThat($actual, $unary);
    }

    /**
     * @dataProvider provConstraintEvaluationSucceeds
     *
     * @param array $ctorArgs arguments passed to createConstraint()
     * @param mixed $actual   actual value
     *
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testConstraintEvaluationSucceeds(array $ctorArgs, mixed $actual): void
    {
        $constraint = static::createConstraint($ctorArgs);
        self::assertThat($actual, $constraint);
    }

    /**
     * @dataProvider provConstraintEvaluationFails
     *
     * @param array $ctorArgs arguments passed to createConstraint()
     *
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testConstraintEvaluationFails(array $ctorArgs, mixed $actual, string $message): void
    {
        $constraint = static::createConstraint($ctorArgs);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $constraint->evaluate($actual);
    }

    /**
     * @dataProvider provNotConstraintEvaluationSucceeds
     *
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testNotConstraintEvaluationSucceeds(array $ctorArgs, mixed $actual): void
    {
        $constraint = self::logicalNot(static::createConstraint($ctorArgs));
        self::assertThat($actual, $constraint);
    }

    /**
     * @dataProvider provNotConstraintEvaluationFails
     *
     * @psalm-param CtorArgs $ctorArgs
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testNotConstraintEvaluationFails(array $ctorArgs, mixed $actual, string $message): void
    {
        $constraint = self::logicalNot(static::createConstraint($ctorArgs));

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $constraint->evaluate($actual);
    }

    /**
     * Returns $constraint wrapped with an UnaryOperator.
     *
     * @throws Exception
     * @throws RuntimeException
     */
    final protected function wrapWithUnaryOperator(
        Constraint $constraint,
        string $operator = 'noop',
        int $precedence = 1
    ): UnaryOperator {
        return new class($constraint, $operator, $precedence) extends UnaryOperator {
            public function __construct(Constraint $constraint, private readonly string $operator, private readonly int $precedence)
            {
                parent::__construct($constraint);
            }

            public function operator(): string
            {
                return $this->operator;
            }

            public function precedence(): int
            {
                return $this->precedence;
            }
        };
    }
}

// vim: syntax=php sw=4 ts=4 et:
