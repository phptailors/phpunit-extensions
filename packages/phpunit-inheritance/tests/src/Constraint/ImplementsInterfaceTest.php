<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Tailors\PHPUnit\Constraint;

use Exception;
use Iterator;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTrait;
use Tailors\PHPUnit\InvalidArgumentException;
use Throwable;
use Traversable;

/**
 * @small
 * @covers \Tailors\PHPUnit\Constraint\ImplementsInterface
 * @covers \Tailors\PHPUnit\Constraint\InheritanceConstraintTestTrait
 * @covers \Tailors\PHPUnit\Inheritance\AbstractConstraint
 * @covers \Tailors\PHPUnit\Inheritance\ConstraintImplementationTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class ImplementsInterfaceTest extends TestCase
{
    use InheritanceConstraintTestTrait;

    // required by InheritanceConstraintTestTrait
    public static function provFailureDescriptionOfCustomUnaryOperator(): iterable
    {
        return [
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'constraint' => ImplementsInterface::create(Throwable::class),
                'subject'    => Iterator::class,
                'expect'     => [
                    'exception' => ExpectationFailedException::class,
                    'message'   => '/Iterator implements interface Throwable/',
                ],
            ],
        ];
    }

    // required by InheritanceConstraintTestTrait
    public static function provFailureDescriptionOfLogicalNotOperator(): iterable
    {
        return [
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'constraint' => ImplementsInterface::create(Throwable::class),
                'subject'    => Exception::class,
                'expect'     => [
                    'exception' => ExpectationFailedException::class,
                    'message'   => '/Exception does not implement interface Throwable/',
                ],
            ],
        ];
    }

    public static function provImplementsInterface(): array
    {
        return [
            // class implements interface
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => Throwable::class,
                'subject'   => Exception::class,
            ],

            // object of class that implements interface
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => Throwable::class,
                'subject'   => new Exception(),
            ],

            // interface that extends interface
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => Traversable::class,
                'subject'   => Iterator::class,
            ],
        ];
    }

    public static function provNotImplementsInterface(): array
    {
        $template = 'Failed asserting that %s implements interface %s.';

        return [
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => Traversable::class,
                'subject'   => Exception::class,
                'message'   => sprintf($template, Exception::class, Traversable::class),
            ],
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => Traversable::class,
                'subject'   => new Exception(),
                'message'   => sprintf($template, 'object '.Exception::class, Traversable::class),
            ],
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => Traversable::class,
                'subject'   => 'lorem ipsum',
                'message'   => sprintf($template, "'lorem ipsum'", Traversable::class),
            ],
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => Traversable::class,
                'subject'   => 123,
                'message'   => sprintf($template, '123', Traversable::class),
            ],
        ];
    }

    public static function provConstraintThrowsInvalidArgumentException(): array
    {
        $message = '/Argument #1 of \S+ must be an interface-string/';

        return [
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'argument' => 'non-interface string',
                'messsage' => $message,
            ],

            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'argument' => Exception::class,
                'messsage' => $message,
            ],

            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'argument' => ExampleTrait::class,
                'messsage' => $message,
            ],
        ];
    }

    /**
     * @dataProvider provImplementsInterface
     *
     * @param mixed $subject
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
     */
    public function testConstraintFails(string $interface, $subject, string $message): void
    {
        $constraint = ImplementsInterface::create($interface);

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        $constraint->evaluate($subject);
    }

    /**
     * @dataProvider provConstraintThrowsInvalidArgumentException
     */
    public function testConstraintThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessageMatches($message);

        ImplementsInterface::create($argument);
    }
}
