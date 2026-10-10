<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\UnaryOperator;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\Rule\AnyInvokedCount;

/**
 * @small
 *
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ExpectExceptionArray = array{exception: class-string<\Exception>, message: string}
 */
trait InheritanceConstraintTestTrait
{
    /**
     * @psalm-return iterable<string, array{
     *      constraint: Constraint,
     *      subject: mixed,
     *      expect: ExpectExceptionArray
     * }>
     */
    abstract public static function provFailureDescriptionOfCustomUnaryOperator(): iterable;

    /** @psalm-param class-string<\Throwable> $exception */
    abstract public function expectException(string $exception): void;

    abstract public function expectExceptionMessage(string $message): void;

    abstract public function expectExceptionMessageMatches(string $message): void;

    /**
     * @psalm-template MockedType
     *
     * @psalm-param class-string<MockedType>|interface-string<MockedType> $className
     *
     * @psalm-return MockBuilder<MockedType>
     */
    abstract public function getMockBuilder(string $className): MockBuilder;

    abstract public static function any(): AnyInvokedCount;

    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    abstract public static function logicalNot(Constraint $constraint): LogicalNot;

    /**
     * @dataProvider provFailureDescriptionOfCustomUnaryOperator
     *
     * @param mixed $subject
     *
     * @psalm-param ExpectExceptionArray $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFailureDescriptionOfCustomUnaryOperator(Constraint $constraint, $subject, array $expect): void
    {
        $noop = $this->getMockBuilder(UnaryOperator::class)
            ->setConstructorArgs([$constraint])
            ->getMockForAbstractClass()
        ;

        $noop->expects(self::any())
            ->method('operator')
            ->willReturn('noop')
        ;
        $noop->expects(self::any())
            ->method('precedence')
            ->willReturn(1)
        ;

        self::expectException($expect['exception']);
        self::expectExceptionMessageMatches($expect['message']);

        $noop->evaluate($subject);
    }

    /**
     * @dataProvider provFailureDescriptionOfLogicalNotOperator
     *
     * @param mixed $subject
     *
     * @psalm-param ExpectExceptionArray $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFailureDescriptionOfLogicalNotOperator(Constraint $constraint, $subject, array $expect): void
    {
        $not = self::logicalNot($constraint);

        self::expectException($expect['exception']);
        self::expectExceptionMessageMatches($expect['message']);

        $not->evaluate($subject);
    }
}
