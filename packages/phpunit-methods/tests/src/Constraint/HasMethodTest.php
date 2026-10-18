<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\Common\AbstractConstraintTestCase;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Polyfill\ExpectExceptionMessageIsOrContainsTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ConstraintClass = HasMethod
 * @psalm-type CtorArgs        = list{string}
 *
 * @template-extends AbstractConstraintTestCase<HasMethod, list{string}>
 */
#[CoversClass(HasMethod::class)]
#[CoversClass(HasMethodTest::class)]
#[Small]
final class HasMethodTest extends AbstractConstraintTestCase
{
    use ExpectExceptionMessageIsOrContainsTrait;
    use ProvHasMethodTrait;

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param CtorArgs $ctorArgs
     */
    public static function createConstraint(array $ctorArgs): Constraint
    {
        return HasMethod::create(...$ctorArgs);
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provConstraintUnaryOperatorFailure(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => ['foo'],
            'actual'   => null,
            'message'  => 'Failed asserting that null has method foo()',
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provConstraintEvaluationSucceeds(): iterable
    {
        foreach (self::provHasMethod() as $key => $case) {
            yield $key => [
                'ctorArgs' => [$case['method']],
                'actual'   => $case['subject'],
            ];
        }
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provConstraintEvaluationFails(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provNotHasMethod();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'ctorArgs' => [$case['method']],
                'actual'   => $case['subject'],
                'message'  => $case['message'],
            ];
        }
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provNotConstraintEvaluationSucceeds(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provNotHasMethod();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'ctorArgs' => [$case['method']],
                'actual'   => $case['subject'],
            ];
        }
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provNotConstraintEvaluationFails(): iterable
    {
        $cases = (function (): iterable {
            yield from self::provHasMethod();
        })();

        foreach ($cases as $key => $case) {
            yield $key => [
                'ctorArgs' => [$case['method']],
                'actual'   => $case['subject'],
                'message'  => $case['message'],
            ];
        }
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCreateThrowsInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIsOrContains(
            'Argument 1 passed to '.HasMethod::class.'::create() must be method specification,'.
            ' \'public function foo??\' (syntax error at "??") given.'
        );

        HasMethod::create('public function foo??');
    }
}

// vim: syntax=php sw=4 ts=4 et:
