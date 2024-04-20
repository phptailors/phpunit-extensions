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
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTrait;
use Tailors\PHPUnit\Inheritance\AbstractConstraint;
use Tailors\PHPUnit\Inheritance\ConstraintImplementationTrait;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(ExtendsClass::class)]
#[CoversClass(InheritanceConstraintTestTrait::class)]
#[CoversClass(AbstractConstraint::class)]
#[CoversClass(ConstraintImplementationTrait::class)]
final class ExtendsClassTest extends TestCase
{
    use InheritanceConstraintTestTrait;

    // required by InheritanceConstraintTestTrait
    public static function provFailureDescriptionOfCustomUnaryOperator(): iterable
    {
        return [
            'ExtendsClassTest.php:'.__LINE__ => [
                'constraint' => ExtendsClass::create(\ErrorException::class),
                'subject'    => \Exception::class,
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
                'constraint' => ExtendsClass::create(\Exception::class),
                'subject'    => \ErrorException::class,
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
                'class'   => \Exception::class,
                'subject' => \ErrorException::class,
            ],

            // object of class that extends class
            'ExtendsClassTest.php:'.__LINE__ => [
                'class'   => \Exception::class,
                'subject' => new \ErrorException(),
            ],

            // class extends class - case insensitive match
            'ExtendsClassTest.php:'.__LINE__ => [
                'class'   => 'eXceptiOn',
                'subject' => 'errOreXceptiOn',
            ],

            // object of class that extends class -- case insensitive match
            'ExtendsClassTest.php:'.__LINE__ => [
                'class'   => 'eXceptiOn',
                'subject' => new \ErrorException(),
            ],
        ];
    }

    public static function provNotExtendsClass(): array
    {
        $template = 'Failed asserting that %s extends class %s.';

        return [
            'ExtendsClassTest.php:'.__LINE__ => [
                'class'   => \Error::class,
                'subject' => \ErrorException::class,
                'message' => sprintf($template, \ErrorException::class, \Error::class),
            ],
            'ExtendsClassTest.php:'.__LINE__ => [
                'class'   => \Error::class,
                'subject' => new \ErrorException(),
                'message' => sprintf($template, 'object '.\ErrorException::class, \Error::class),
            ],
            'ExtendsClassTest.php:'.__LINE__ => [
                'class'   => \Error::class,
                'subject' => 'lorem ipsum',
                'message' => sprintf($template, "'lorem ipsum'", \Error::class),
            ],
            'ExtendsClassTest.php:'.__LINE__ => [
                'class'   => \Error::class,
                'subject' => 123,
                'message' => sprintf($template, '123', \Error::class),
            ],
        ];
    }

    public static function provThrowsInvalidArgumentException(): array
    {
        $message = '/Argument 1 passed to \S+ must be a class-string/';

        return [
            'ExtendsClassTest.php:'.__LINE__ => [
                'argument' => 'non-class string',
                'message'  => $message,
            ],

            'ExtendsClassTest.php:'.__LINE__ => [
                'argument' => \Throwable::class,
                'message'  => $message,
            ],

            'ExtendsClassTest.php:'.__LINE__ => [
                'argument' => ExampleTrait::class,
                'message'  => $message,
            ],
        ];
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provExtendsClass')]
    public function testConstraintSucceeds(string $class, $subject): void
    {
        $constraint = ExtendsClass::create($class);

        self::assertTrue($constraint->evaluate($subject, '', true));
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provNotExtendsClass')]
    public function testConstraintFails(string $class, $subject, string $message): void
    {
        $constraint = ExtendsClass::create($class);

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        $constraint->evaluate($subject);
    }

    #[DataProvider('provThrowsInvalidArgumentException')]
    public function testThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessageMatches($message);

        ExtendsClass::create($argument);
    }
}
