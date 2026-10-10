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
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTrait;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\ExtendsClass
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ExpectExceptionArray = array{exception: class-string<\Exception>, message: string}
 */
final class ExtendsClassTest extends TestCase
{
    use InheritanceConstraintTestTrait;

    /**
     * @psalm-return iterable<string, array{
     *      constraint: Constraint,
     *      subject: mixed,
     *      expect: ExpectExceptionArray
     * }>
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public static function provFailureDescriptionOfCustomUnaryOperator(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => ExtendsClass::create(\ErrorException::class),
            'subject'    => \Exception::class,
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => '/Exception extends class ErrorException/',
            ],
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      constraint: Constraint,
     *      subject: mixed,
     *      expect: ExpectExceptionArray
     * }>
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public static function provFailureDescriptionOfLogicalNotOperator(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'constraint' => ExtendsClass::create(\Exception::class),
            'subject'    => \ErrorException::class,
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => '/ErrorException does not extend class Exception/',
            ],
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      class: string,
     *      subject: mixed
     * }>
     */
    public static function provExtendsClass(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'class'   => \Exception::class,
            'subject' => \ErrorException::class,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'class'   => \Exception::class,
            'subject' => new \ErrorException(),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'class'   => 'eXceptiOn',
            'subject' => 'errOreXceptiOn',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'class'   => 'eXceptiOn',
            'subject' => new \ErrorException(),
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      class: class-string,
     *      subject: mixed,
     *      message: string
     * }>
     */
    public static function provNotExtendsClass(): iterable
    {
        $template = 'Failed asserting that %s extends class %s.';

        yield basename(__FILE__).':'.__LINE__ => [
            'class'   => \Error::class,
            'subject' => \ErrorException::class,
            'message' => sprintf($template, \ErrorException::class, \Error::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'class'   => \Error::class,
            'subject' => new \ErrorException(),
            'message' => sprintf($template, 'object '.\ErrorException::class, \Error::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'class'   => \Error::class,
            'subject' => 'lorem ipsum',
            'message' => sprintf($template, "'lorem ipsum'", \Error::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'class'   => \Error::class,
            'subject' => 123,
            'message' => sprintf($template, '123', \Error::class),
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      argument: string,
     *      message: string
     * }>
     */
    public static function provThrowsInvalidArgumentException(): iterable
    {
        $message = '/Argument 1 passed to \S+ must be a class-string/';

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => 'non-class string',
            'message'  => $message,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => \Throwable::class,
            'message'  => $message,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => ExampleTrait::class,
            'message'  => $message,
        ];
    }

    /**
     * @dataProvider provExtendsClass
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstraintSucceeds(string $class, $subject): void
    {
        $constraint = ExtendsClass::create($class);

        self::assertTrue($constraint->evaluate($subject, '', true));
    }

    /**
     * @dataProvider provNotExtendsClass
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstraintFails(string $class, $subject, string $message): void
    {
        $constraint = ExtendsClass::create($class);

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        $constraint->evaluate($subject);
    }

    /**
     * @dataProvider provThrowsInvalidArgumentException
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessageMatches($message);

        ExtendsClass::create($argument);
    }
}
