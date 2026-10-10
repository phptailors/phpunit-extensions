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
 * @covers \Tailors\PHPUnit\Constraint\ImplementsInterface
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ExpectExceptionArray = array{exception: class-string<\Exception>, message: string}
 */
final class ImplementsInterfaceTest extends TestCase
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
            'constraint' => ImplementsInterface::create(\Throwable::class),
            'subject'    => \Iterator::class,
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => '/Iterator implements interface Throwable/',
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
            'constraint' => ImplementsInterface::create(\Throwable::class),
            'subject'    => \Exception::class,
            'expect'     => [
                'exception' => ExpectationFailedException::class,
                'message'   => '/Exception does not implement interface Throwable/',
            ],
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      interface: string,
     *      subject: mixed
     * }>
     */
    public static function provImplementsInterface(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'interface' => \Throwable::class,
            'subject'   => \Exception::class,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'interface' => \Throwable::class,
            'subject'   => new \Exception(),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'interface' => \Traversable::class,
            'subject'   => \Iterator::class,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'interface' => 'tHrowAble',
            'subject'   => 'eXceptiOn',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'interface' => 'tHrowAble',
            'subject'   => new \Exception(),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'interface' => 'tRaversAble',
            'subject'   => 'iteRator',
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      interface: interface-string,
     *      subject: mixed,
     *      message: string
     * }>
     */
    public static function provNotImplementsInterface(): iterable
    {
        $template = 'Failed asserting that %s implements interface %s.';

        yield basename(__FILE__).':'.__LINE__ => [
            'interface' => \Traversable::class,
            'subject'   => \Exception::class,
            'message'   => sprintf($template, \Exception::class, \Traversable::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'interface' => \Traversable::class,
            'subject'   => new \Exception(),
            'message'   => sprintf($template, 'object '.\Exception::class, \Traversable::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'interface' => \Traversable::class,
            'subject'   => 'lorem ipsum',
            'message'   => sprintf($template, "'lorem ipsum'", \Traversable::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'interface' => \Traversable::class,
            'subject'   => 123,
            'message'   => sprintf($template, '123', \Traversable::class),
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
        $message = '/Argument 1 passed to \S+ must be an interface-string/';

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => 'non-interface string',
            'message'  => $message,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => \Exception::class,
            'message'  => $message,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => ExampleTrait::class,
            'message'  => $message,
        ];
    }

    /**
     * @dataProvider provImplementsInterface
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstraintSucceeds(string $interface, $subject): void
    {
        $constraint = ImplementsInterface::create($interface);

        self::assertTrue($constraint->evaluate($subject, '', true));
    }

    /**
     * @dataProvider provNotImplementsInterface
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testConstraintFails(string $interface, $subject, string $message): void
    {
        $constraint = ImplementsInterface::create($interface);

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

        ImplementsInterface::create($argument);
    }
}
