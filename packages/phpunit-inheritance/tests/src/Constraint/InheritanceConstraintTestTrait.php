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
use PHPUnit\Framework\Constraint\UnaryOperator;
use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ExpectExceptionArray = array{exception: class-string<\Exception>, message: string}
 *
 * @psalm-require-extends TestCase
 *
 * @var TestCase $__phpactor__workaround__unused_import__TestCase
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

    /**
     * @dataProvider provFailureDescriptionOfCustomUnaryOperator
     *
     * @psalm-param ExpectExceptionArray $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFailureDescriptionOfCustomUnaryOperator(Constraint $constraint, mixed $subject, array $expect): void
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

        $this->expectException($expect['exception']);
        $this->expectExceptionMessageMatches($expect['message']);

        $noop->evaluate($subject);
    }

    /**
     * @dataProvider provFailureDescriptionOfLogicalNotOperator
     *
     * @psalm-param ExpectExceptionArray $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testFailureDescriptionOfLogicalNotOperator(Constraint $constraint, mixed $subject, array $expect): void
    {
        $not = self::logicalNot($constraint);

        $this->expectException($expect['exception']);
        $this->expectExceptionMessageMatches($expect['message']);

        $not->evaluate($subject);
    }
}
