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
#[CoversClass(ImplementsInterface::class)]
#[CoversClass(InheritanceConstraintTestTrait::class)]
#[CoversClass(AbstractConstraint::class)]
#[CoversClass(ConstraintImplementationTrait::class)]
final class ImplementsInterfaceTest extends TestCase
{
    use InheritanceConstraintTestTrait;

    // required by InheritanceConstraintTestTrait
    public static function provFailureDescriptionOfCustomUnaryOperator(): iterable
    {
        return [
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'constraint' => ImplementsInterface::create(\Throwable::class),
                'subject'    => \Iterator::class,
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
                'constraint' => ImplementsInterface::create(\Throwable::class),
                'subject'    => \Exception::class,
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
                'interface' => \Throwable::class,
                'subject'   => \Exception::class,
            ],

            // object of class that implements interface
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => \Throwable::class,
                'subject'   => new \Exception(),
            ],

            // interface that extends interface
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject'   => \Iterator::class,
            ],

            // class implements interface -- case insensitive match
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => 'tHrowAble',
                'subject'   => 'eXceptiOn',
            ],

            // object of class that implements interface -- case insensitive match
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => 'tHrowAble',
                'subject'   => new \Exception(),
            ],

            // interface that extends interface -- case insensitive match
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => 'tRaversAble',
                'subject'   => 'iteRator',
            ],
        ];
    }

    public static function provNotImplementsInterface(): array
    {
        $template = 'Failed asserting that %s implements interface %s.';

        return [
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject'   => \Exception::class,
                'message'   => sprintf($template, \Exception::class, \Traversable::class),
            ],
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject'   => new \Exception(),
                'message'   => sprintf($template, 'object '.\Exception::class, \Traversable::class),
            ],
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject'   => 'lorem ipsum',
                'message'   => sprintf($template, "'lorem ipsum'", \Traversable::class),
            ],
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject'   => 123,
                'message'   => sprintf($template, '123', \Traversable::class),
            ],
        ];
    }

    public static function provConstraintThrowsInvalidArgumentException(): array
    {
        $message = '/Argument 1 passed to \S+ must be an interface-string/';

        return [
            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'argument' => 'non-interface string',
                'message'  => $message,
            ],

            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'argument' => \Exception::class,
                'message'  => $message,
            ],

            'ImplementsInterfaceTest.php:'.__LINE__ => [
                'argument' => ExampleTrait::class,
                'message'  => $message,
            ],
        ];
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provImplementsInterface')]
    public function testConstraintSucceeds(string $interface, $subject): void
    {
        $constraint = ImplementsInterface::create($interface);

        self::assertTrue($constraint->evaluate($subject, '', true));
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provNotImplementsInterface')]
    public function testConstraintFails(string $interface, $subject, string $message): void
    {
        $constraint = ImplementsInterface::create($interface);

        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        $constraint->evaluate($subject);
    }

    #[DataProvider('provConstraintThrowsInvalidArgumentException')]
    public function testConstraintThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessageMatches($message);

        ImplementsInterface::create($argument);
    }
}
