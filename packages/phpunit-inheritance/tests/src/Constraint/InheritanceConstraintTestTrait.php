<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
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
 * @psalm-internal Tailors\PHPUnit
 */
trait InheritanceConstraintTestTrait
{
    abstract public static function provFailureDescriptionOfCustomUnaryOperator(): iterable;

    abstract public function expectException(string $exception): void;

    abstract public function expectExceptionMessage(string $message): void;

    abstract public function getMockBuilder(string $className): MockBuilder;

    abstract public static function any(): AnyInvokedCount;

    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    abstract public static function logicalNot(Constraint $constraint): LogicalNot;

    /**
     * @dataProvider provFailureDescriptionOfCustomUnaryOperator
     *
     * @param mixed $subject
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

        $regexp = '/Iterator implements interface Throwable/';

        self::expectException($expect['exception']);
        self::expectExceptionMessageMatches($expect['message']);

        $noop->evaluate($subject);

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provFailureDescriptionOfLogicalNotOperator
     *
     * @param mixed $subject
     */
    public function testFailureDescriptionOfLogicalNotOperator(Constraint $constraint, $subject, array $expect): void
    {
        $not = self::logicalNot($constraint);

        self::expectException($expect['exception']);
        self::expectExceptionMessageMatches($expect['message']);

        $not->evaluate($subject);

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd
}
