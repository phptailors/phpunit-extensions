<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ImplementsInterface;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTrait;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(ImplementsInterfaceTrait::class)]
final class ImplementsInterfaceTraitTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public static function provImplementsInterface(): array
    {
        $template = 'Failed asserting that %s does not implement interface %s.';

        return [
            'ImplementsInterfaceTraitTest.php:'.__LINE__ => [
                'interface' => \Throwable::class,
                'subject'   => \Exception::class,
                'message'   => sprintf($template, \Exception::class, \Throwable::class),
            ],
            'ImplementsInterfaceTraitTest.php:'.__LINE__ => [
                'interface' => \Throwable::class,
                'subject'   => new \Exception(),
                'message'   => sprintf($template, 'object '.\Exception::class, \Throwable::class),
            ],
            'ImplementsInterfaceTraitTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject'   => \Iterator::class,
                'message'   => sprintf($template, \Iterator::class, \Traversable::class),
            ],
        ];
    }

    public static function provNotImplementsInterface(): array
    {
        $template = 'Failed asserting that %s implements interface %s.';

        return [
            'ImplementsInterfaceTraitTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject'   => \Exception::class,
                'message'   => sprintf($template, \Exception::class, \Traversable::class),
            ],
            'ImplementsInterfaceTraitTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject'   => new \Exception(),
                'message'   => sprintf($template, 'object '.\Exception::class, \Traversable::class),
            ],
            'ImplementsInterfaceTraitTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject'   => 'lorem ipsum',
                'message'   => sprintf($template, "'lorem ipsum'", \Traversable::class),
            ],
            'ImplementsInterfaceTraitTest.php:'.__LINE__ => [
                'interface' => \Traversable::class,
                'subject'   => 123,
                'message'   => sprintf($template, '123', \Traversable::class),
            ],
        ];
    }

    /**
     * @param object|string $subject
     */
    #[DataProvider('provImplementsInterface')]
    public function testAssertImplementsInterfaceSucceeds(string $interface, $subject, string $message): void
    {
        self::assertImplementsInterface($interface, $subject);
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provNotImplementsInterface')]
    public function testAssertImplementsInterfaceFails(string $interface, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertImplementsInterface($interface, $subject);
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provNotImplementsInterface')]
    public function testAssertNotImplementsInterfaceSucceeds(string $interface, $subject, string $message): void
    {
        self::assertNotImplementsInterface($interface, $subject);
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provImplementsInterface')]
    public function testAssertNotImplementsInterfaceFails(string $interface, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertNotImplementsInterface($interface, $subject);
    }

    /**
     * @param mixed $subject
     */
    #[DataProvider('provNotImplementsInterface')]
    public function testImplementsInterfaceFails(string $interface, $subject, string $message): void
    {
        self::assertThat($subject, self::logicalNot(self::implementsInterface($interface)));
    }

    public static function provImplementsInterfaceThrowsInvalidArgumentException(): array
    {
        $template = 'Argument 1 passed to %s::create() must be an interface-string, \'%s\' given';

        return [
            'ImplementsInterfaceTraitTest.php:'.__LINE__ => [
                'argument' => 'non-interface string',
                'message'  => sprintf($template, ImplementsInterface::class, 'non-interface string'),
            ],

            'ImplementsInterfaceTraitTest.php:'.__LINE__ => [
                'argument' => \Exception::class,
                'message'  => sprintf($template, ImplementsInterface::class, addslashes(\Exception::class)),
            ],

            'ImplementsInterfaceTraitTest.php:'.__LINE__ => [
                'argument' => ExampleTrait::class,
                'message'  => sprintf($template, ImplementsInterface::class, addslashes(ExampleTrait::class)),
            ],
        ];
    }

    #[DataProvider('provImplementsInterfaceThrowsInvalidArgumentException')]
    public function testImplementsInterfaceThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage($message);

        self::implementsInterface($argument);
    }
}

// vim: syntax=php sw=4 ts=4 et:
