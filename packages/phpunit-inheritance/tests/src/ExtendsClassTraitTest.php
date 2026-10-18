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
use Tailors\PHPUnit\Constraint\ExtendsClass;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTrait;
use Tailors\PHPUnit\Polyfill\ExpectExceptionMessageIsOrContainsTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(ExtendsClassTraitTest::class)]
#[Small]
final class ExtendsClassTraitTest extends TestCase
{
    use ExpectExceptionMessageIsOrContainsTrait;
    use ExtendsClassTrait;

    /**
     * @psalm-return iterable<string, array{
     *      class: string,
     *      subject: mixed,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provExtendsClass(): iterable
    {
        $template = 'Failed asserting that %s does not extend class %s.';

        yield basename(__FILE__).':'.__LINE__ => [
            'class'   => \Exception::class,
            'subject' => \ErrorException::class,
            'message' => sprintf($template, \ErrorException::class, \Exception::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'class'   => \Exception::class,
            'subject' => new \ErrorException(),
            'message' => sprintf($template, 'object '.\ErrorException::class, \Exception::class),
        ];
    }

    /**
     * @psalm-return iterable<string, array{
     *      class: string,
     *      subject: mixed,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
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
     * @psalm-return iterable<string, array{class: string, subject: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provExtendsClassSucceeds(): iterable
    {
        foreach (self::provExtendsClass() as $key => $case) {
            yield $key => [
                'class'   => $case['class'],
                'subject' => $case['subject'],
            ];
        }
    }

    /**
     * @psalm-return iterable<string, array{class: string, subject: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provNotExtendsClassSucceeds(): iterable
    {
        foreach (self::provNotExtendsClass() as $key => $case) {
            yield $key => [
                'class'   => $case['class'],
                'subject' => $case['subject'],
            ];
        }
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provExtendsClassSucceeds')]
    public function testExtendsClassSucceeds(string $class, $subject): void
    {
        self::assertThat($subject, self::extendsClass($class));
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provExtendsClassSucceeds')]
    public function testAssertExtendsClassSucceeds(string $class, $subject): void
    {
        self::assertExtendsClass($class, $subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotExtendsClassSucceeds')]
    public function testLogicalNotExtendsClassSucceeds(string $class, $subject): void
    {
        self::assertThat($subject, self::logicalNot(self::extendsClass($class)));
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotExtendsClassSucceeds')]
    public function testAssertNotExtendsClassSucceeds(string $class, $subject): void
    {
        self::assertNotExtendsClass($class, $subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotExtendsClass')]
    public function testAssertExtendsClassFails(string $class, $subject, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageIsOrContains($message);

        self::assertExtendsClass($class, $subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provExtendsClass')]
    public function testAssertNotExtendsClassFails(string $class, $subject, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageIsOrContains($message);

        self::assertNotExtendsClass($class, $subject);
    }

    /**
     * @psalm-return iterable<string, array{
     *      argument: string,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provExtendsClassThrowsInvalidArgumentException(): iterable
    {
        $template = 'Argument 1 passed to %s::create() must be a class-string';

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => 'non-class string',
            'message'  => sprintf($template, ExtendsClass::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => \Throwable::class,
            'message'  => sprintf($template, ExtendsClass::class),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'argument' => ExampleTrait::class,
            'message'  => sprintf($template, ExtendsClass::class),
        ];
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provExtendsClassThrowsInvalidArgumentException')]
    public function testExtendsClassThrowsInvalidArgumentException(string $argument, string $message): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIsOrContains($message);

        self::extendsClass($argument);
    }
}

// vim: syntax=php sw=4 ts=4 et:
