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
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ImplementsInterface;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTrait;
use Tailors\PHPUnit\Polyfill\ExpectExceptionMessageIsOrContainsTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(ImplementsInterfaceTraitTest::class)]
#[Small]
final class ImplementsInterfaceTraitTest extends TestCase
{
    use ExpectExceptionMessageIsOrContainsTrait;
    use ImplementsInterfaceTrait;

    /**
     * @psalm-return iterable<string, array{
     *      interface: string,
     *      subject: mixed,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provImplementsInterface(): iterable
    {
        $template = 'Failed asserting that %s does not implement interface %s.';

        yield basename(__FILE__).':'.__LINE__ => [
            'interface' => \Throwable::class,
            'subject'   => \Exception::class,
            'message'   => sprintf($template, \Exception::class, \Throwable::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'interface' => \Throwable::class,
            'subject'   => new \Exception(),
            'message'   => sprintf($template, 'object '.\Exception::class, \Throwable::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'interface' => \Traversable::class,
            'subject'   => \Iterator::class,
            'message'   => sprintf($template, \Iterator::class, \Traversable::class),
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      interface: string,
     *      subject: mixed,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
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
     * @psalm-return iterable<string, array{interface: string, subject: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provImplementsInterfaceSucceeds(): iterable
    {
        foreach (self::provImplementsInterface() as $key => $case) {
            yield $key => [
                'interface' => $case['interface'],
                'subject'   => $case['subject'],
            ];
        }
    }

    /**
     * @psalm-return iterable<string, array{interface: string, subject: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provNotImplementsInterfaceSucceeds(): iterable
    {
        foreach (self::provNotImplementsInterface() as $key => $case) {
            yield $key => [
                'interface' => $case['interface'],
                'subject'   => $case['subject'],
            ];
        }
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provImplementsInterfaceSucceeds')]
    public function testImplementsInterfaceSucceeds(string $interface, $subject): void
    {
        self::assertThat($subject, self::implementsInterface($interface));
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provImplementsInterfaceSucceeds')]
    public function testAssertImplementsInterfaceSucceeds(string $interface, $subject): void
    {
        self::assertImplementsInterface($interface, $subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotImplementsInterfaceSucceeds')]
    public function testLogicalNotImplementsInterfaceSucceeds(string $interface, $subject): void
    {
        self::assertThat($subject, self::logicalNot(self::implementsInterface($interface)));
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotImplementsInterfaceSucceeds')]
    public function testAssertNotImplementsInterfaceSucceeds(string $interface, $subject): void
    {
        self::assertNotImplementsInterface($interface, $subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotImplementsInterface')]
    public function testAssertImplementsInterfaceFails(string $interface, $subject, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageIsOrContains($message);

        self::assertImplementsInterface($interface, $subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provImplementsInterface')]
    public function testAssertNotImplementsInterfaceFails(string $interface, $subject, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageIsOrContains($message);

        self::assertNotImplementsInterface($interface, $subject);
    }

    /**
     * @psalm-return iterable<string, array{
     *      argument: string,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provImplementsInterfaceThrowsInvalidArgumentException(): iterable
    {
        $template = 'Argument 1 passed to %s::create() must be an interface-string, \'%s\' given';

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => 'non-interface string',
            'message'  => sprintf($template, ImplementsInterface::class, 'non-interface string'),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => \Exception::class,
            'message'  => sprintf($template, ImplementsInterface::class, addslashes(\Exception::class)),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => ExampleTrait::class,
            'message'  => sprintf($template, ImplementsInterface::class, addslashes(ExampleTrait::class)),
        ];
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provImplementsInterfaceThrowsInvalidArgumentException')]
    public function testImplementsInterfaceThrowsInvalidArgumentException(string $argument, string $message): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIsOrContains($message);

        self::implementsInterface($argument);
    }
}

// vim: syntax=php sw=4 ts=4 et:
