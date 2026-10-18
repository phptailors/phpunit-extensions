<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ValueSelector;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(ClassPropertySelector::class)]
#[CoversClass(ClassPropertySelectorTest::class)]
#[Small]
final class ClassPropertySelectorTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsValueSelectorInterface(): void
    {
        self::assertInstanceOf(ValueSelectorInterface::class, new ClassPropertySelector());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractPropertySelector(): void
    {
        self::assertInstanceOf(AbstractPropertySelector::class, new ClassPropertySelector());
    }

    /**
     * @psalm-return iterable<string, array{
     *      subject: mixed,
     *      expect: mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provSupports(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => 'foo',
            'expect'  => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => [],
            'expect'  => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => self::class,
            'expect'  => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => (new class() {})::class,
            'expect'  => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => ClassPropertySelector::class,
            'expect'  => true,
        ];
    }

    /**
     * @param mixed $subject
     * @param mixed $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provSupports')]
    public function testSupports($subject, $expect): void
    {
        $selector = new ClassPropertySelector();
        self::assertSame($expect, $selector->supports($subject));
    }

    /**
     * @psalm-return iterable<string, array{
     *      class: string,
     *      key: array-key,
     *      return: bool,
     *      expect: mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provSelect(): iterable
    {
        // #0
        yield basename(__FILE__).':'.__LINE__ => [
            'class'  => (new class() {
                public static string $foo = 'FOO';
            })::class,
            'key'    => 'foo',
            'return' => true,
            'expect' => 'FOO',
        ];

        // #1
        yield basename(__FILE__).':'.__LINE__ => [
            'class'  => (new class() {
                public static string $foo = 'FOO';
            })::class,
            'key'    => 'bar',
            'return' => false,
            'expect' => null,
        ];

        // #2
        yield basename(__FILE__).':'.__LINE__ => [
            'class'  => (new class() {
                public static function foo(): string
                {
                    return 'FOO';
                }
            })::class,
            'key'    => 'foo()',
            'return' => true,
            'expect' => 'FOO',
        ];

        // #3
        yield basename(__FILE__).':'.__LINE__ => [
            'class'  => (new class() {
                public static function foo(): string
                {
                    return 'FOO';
                }
            })::class,
            'key'    => 'bar()',
            'return' => false,
            'expect' => null,
        ];
    }

    /**
     * @param mixed $key
     * @param mixed $return
     * @param mixed $expect
     *
     * @psalm-param array-key $key
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provSelect')]
    public function testSelect(string $class, $key, $return, $expect): void
    {
        $selector = new ClassPropertySelector();
        self::assertSame($return, $selector->select($class, $key, $retval));
        self::assertSame($expect, $retval);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnPrivateMethod(): void
    {
        $class = (new class() {
            private static function foo(): void {}
        })::class;
        $selector = new ClassPropertySelector();

        self::expectException(\Error::class);
        self::expectExceptionMessage('private method');

        $selector->select($class, 'foo()');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnPrivateAttribute(): void
    {
        $class = (new class() {
            private string $foo = 'FOO';
        })::class;
        $selector = new ClassPropertySelector();

        self::expectException(\Error::class);
        self::expectExceptionMessage('private property');

        $selector->select($class, 'foo');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnNonStaticMethod(): void
    {
        $class = ClassWithNonStaticMethodFooBLSGG::class;
        $selector = new ClassPropertySelector();

        // One of the two branches is always uncovered, so I just "code-coverage-ignore" them.
        if (PHP_VERSION_ID < 80000) {
            // Because TestCase::expectDeprecation() is removed in PHPUnit 10.
            // @codeCoverageIgnoreStart
            try {
                set_error_handler(static function (int $severity, string $message): void {
                    throw new \ErrorException($message, $severity);
                });
                self::expectException(\ErrorException::class);
                self::expectExceptionMessage('should not be called statically');

                $selector->select($class, 'foo()');
            } finally {
                restore_error_handler();
            }
        // @codeCoverageIgnoreEnd
        } else {
            // @codeCoverageIgnoreStart
            self::expectException(\TypeError::class);
            self::expectExceptionMessage('cannot be called statically');
            $selector->select($class, 'foo()');
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnNonStaticProperty(): void
    {
        $class = (new class() {
            public string $foo = 'FOO';
        })::class;
        $selector = new ClassPropertySelector();

        self::expectException(\Error::class);
        self::expectExceptionMessage('undeclared static property');

        $selector->select($class, 'foo');
    }

    /**
     * @psalm-return iterable<string, array{key: string}>
     *
     * @codeCoverageIgnore
     */
    public static function provSelectThrowsOnNonClass(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'key' => 'foo',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'key' => 'foo()',
        ];
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provSelectThrowsOnNonClass')]
    public function testSelectThrowsOnNonClass(string $key): void
    {
        $selector = new ClassPropertySelector();

        $message = sprintf(
            'Argument 1 passed to %s::select() must be a class, %s given',
            AbstractValueSelector::class,
            gettype(123)
        );
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage($message);

        $selector->select(123, $key);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSubject(): void
    {
        $selector = new ClassPropertySelector();
        self::assertSame('a class', $selector->subject());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectable(): void
    {
        $selector = new ClassPropertySelector();
        self::assertSame('properties', $selector->selectable());
    }
}
// vim: syntax=php sw=4 ts=4 et:
