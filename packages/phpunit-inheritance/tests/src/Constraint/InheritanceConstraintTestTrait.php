<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Attributes\DataProvider;
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
 */
trait InheritanceConstraintTestTrait
{
    abstract public static function provFailureDescriptionOfCustomUnaryOperator(): iterable;

    abstract public function expectException(string $exception): void;

    abstract public function expectExceptionMessage(string $message): void;

    abstract public function getMockBuilder(string $className): MockBuilder;

    abstract public function any(): AnyInvokedCount;

    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    abstract public static function logicalNot(Constraint $constraint): LogicalNot;

    /**
     * @param mixed $subject
     */
    #[DataProvider('provFailureDescriptionOfCustomUnaryOperator')]
    public function testFailureDescriptionOfCustomUnaryOperator(Constraint $constraint, $subject, array $expect): void
    {
        $noop = new class($constraint) extends UnaryOperator {
            public function operator(): string
            {
                return 'noop';
            }

            public function precedence(): int
            {
                return 1;
            }
        };

        $regexp = '/Iterator implements interface Throwable/';

        self::expectException($expect['exception']);
        self::expectExceptionMessageMatches($expect['message']);

        $noop->evaluate($subject);

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @param mixed $subject
     */
    #[DataProvider('provFailureDescriptionOfLogicalNotOperator')]
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
