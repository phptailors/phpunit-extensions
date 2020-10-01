<?php declare(strict_types=1);

/*
 * This file is part of php-fox/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace PHPFox\PHPUnit\Constraint;

use Error;
use ErrorException;
use Exception;
use PHPFox\PHPUnit\Examples\Inheritance\ExampleTrait;
use PHPFox\PHPUnit\InvalidArgumentException;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * @small
 * @covers \PHPFox\PHPUnit\Constraint\AbstractInheritanceConstraint
 * @covers \PHPFox\PHPUnit\Constraint\ExtendsClass
 * @covers \PHPFox\PHPUnit\Constraint\InheritanceConstraintTestTrait
 *
 * @internal
 */
final class ExtendsClassTest extends TestCase
{
    use InheritanceConstraintTestTrait;

    // required by InheritanceConstraintTestTrait
    public static function provFailureDescriptionOfCustomUnaryOperator(): iterable
    {
        return [
            'ExtendsClassTest.php:'.__LINE__ => [
                'constraint' => ExtendsClass::fromClassString(ErrorException::class),
                'subject'    => Exception::class,
                'expect'     => [
                    'exception' => ExpectationFailedException::class,
                    'message'   => '/Exception extends class ErrorException/',
                ],
            ],
        ];
    }

    // required by InheritanceConstraintTestTrait
    public static function provFailureDescriptionOfLogicalNotOperator(): iterable
    {
        return [
            'ExtendsClassTest.php:'.__LINE__ => [
                'constraint' => ExtendsClass::fromClassString(Exception::class),
                'subject'    => ErrorException::class,
                'expect'     => [
                    'exception' => ExpectationFailedException::class,
                    'message'   => '/ErrorException does not extend class Exception/',
                ],
            ],
        ];
    }

    public static function provExtendsClass(): array
    {
        return [
            // class extends class
            'ExtendsClassTest.php:'.__LINE__ => [
                'class'   => Exception::class,
                'subject' => ErrorException::class,
            ],

            // object of class that extends class
            'ExtendsClassTest.php:'.__LINE__ => [
                'class'   => Exception::class,
                'subject' => new ErrorException(),
            ],
        ];
    }

    public static function provNotExtendsClass(): array
    {
        $template = 'Failed asserting that %s extends class %s.';

        return [
            'ExtendsClassTest.php:'.__LINE__ => [
                'class'   => Error::class,
                'subject' => ErrorException::class,
                'message' => sprintf($template, ErrorException::class, Error::class),
            ],
            'ExtendsClassTest.php:'.__LINE__ => [
                'class'   => Error::class,
                'subject' => new ErrorException(),
                'message' => sprintf($template, 'object '.ErrorException::class, Error::class),
            ],
            'ExtendsClassTest.php:'.__LINE__ => [
                'class'   => Error::class,
                'subject' => 'lorem ipsum',
                'message' => sprintf($template, "'lorem ipsum'", Error::class),
            ],
            'ExtendsClassTest.php:'.__LINE__ => [
                'class'   => Error::class,
                'subject' => 123,
                'message' => sprintf($template, '123', Error::class),
            ],
        ];
    }

    public static function provThrowsInvalidArgumentException(): array
    {
        $message = '/Argument #1 of \S+ must be a class-string/';

        return [
            'ExtendsClassTest.php:'.__LINE__ => [
                'argument' => 'non-class string',
                'messsage' => $message,
            ],

            'ExtendsClassTest.php:'.__LINE__ => [
                'argument' => Throwable::class,
                'messsage' => $message,
            ],

            'ExtendsClassTest.php:'.__LINE__ => [
                'argument' => ExampleTrait::class,
                'messsage' => $message,
            ],
        ];
    }

    /**
     * @dataProvider provExtendsClass
     *
     * @param mixed $subject
     */
    public function testConstraintSucceeds(string $class, $subject): void
    {
        $constraint = ExtendsClass::fromClassString($class);

        self::assertTrue($constraint->evaluate($subject, '', true));
    }

    /**
     * @dataProvider provNotExtendsClass
     *
     * @param mixed $subject
     */
    public function testConstraintFails(string $class, $subject, string $message): void
    {
        $constraint = ExtendsClass::fromClassString($class);

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        $constraint->evaluate($subject);
    }

    /**
     * @dataProvider provThrowsInvalidArgumentException
     */
    public function testThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessageMatches($message);

        ExtendsClass::fromClassString($argument);
    }
}
