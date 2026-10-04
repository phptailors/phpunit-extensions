<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ValueSelector;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ValueSelector\AbstractPropertySelector
 * @covers \Tailors\PHPUnit\ValueSelector\AbstractValueSelector
 * @covers \Tailors\PHPUnit\ValueSelector\ClassPropertySelector
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
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

    // @codeCoverageIgnoreStart
    /**
     * @psalm-return iterable<string, array{
     *      subject: mixed,
     *      expect: mixed
     * }>
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
            'subject' => get_class(new class() {}),
            'expect'  => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => ClassPropertySelector::class,
            'expect'  => true,
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provSupports
     *
     * @param mixed $subject
     * @param mixed $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSupports($subject, $expect): void
    {
        $selector = new ClassPropertySelector();
        self::assertSame($expect, $selector->supports($subject));
    }

    // @codeCoverageIgnoreStart
    /**
     * @psalm-return iterable<string, array{
     *      class: string,
     *      key: array-key,
     *      return: bool,
     *      expect: mixed
     * }>
     */
    public static function provSelect(): iterable
    {
        // #0
        yield basename(__FILE__).':'.__LINE__ => [
            'class'  => get_class(new class() {
                /** @var string */
                public static $foo = 'FOO';
            }),
            'key'    => 'foo',
            'return' => true,
            'expect' => 'FOO',
        ];

        // #1
        yield basename(__FILE__).':'.__LINE__ => [
            'class'  => get_class(new class() {
                /** @var string */
                public static $foo = 'FOO';
            }),
            'key'    => 'bar',
            'return' => false,
            'expect' => null,
        ];

        // #2
        yield basename(__FILE__).':'.__LINE__ => [
            'class'  => get_class(new class() {
                public static function foo(): string
                {
                    return 'FOO';
                }
            }),
            'key'    => 'foo()',
            'return' => true,
            'expect' => 'FOO',
        ];

        // #3
        yield basename(__FILE__).':'.__LINE__ => [
            'class'  => get_class(new class() {
                public static function foo(): string
                {
                    return 'FOO';
                }
            }),
            'key'    => 'bar()',
            'return' => false,
            'expect' => null,
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provSelect
     *
     * @param mixed $key
     * @param mixed $return
     * @param mixed $expect
     *
     * @psalm-param array-key $key
     *
     * @psalm-suppress MissingThrowsDocblock
     */
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
        $class = get_class(new class() {
            private static function foo(): void
            {
                // @codeCoverageIgnoreStart
            }

            // @codeCoverageIgnoreEnd
        });
        $selector = new ClassPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private method');

        $selector->select($class, 'foo()');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnPrivateAttribute(): void
    {
        $class = get_class(new class() {
            /** @var string */
            private $foo = 'FOO';
        });
        $selector = new ClassPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private property');

        $selector->select($class, 'foo');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnNonStaticMethod(): void
    {
        $class = ClassWithNonStaticMethodFooBLSGG::class;
        $selector = new ClassPropertySelector();

        if (PHP_VERSION_ID < 80000) {
            // Because expectDeprecation() is removed in phpunit 10.
            try {
                set_error_handler(static function (int $severity, string $message): void {
                    throw new \ErrorException($message, $severity);
                });
                $this->expectException(\ErrorException::class);
                $this->expectExceptionMessage('should not be called statically');

                $selector->select($class, 'foo()');
            } finally {
                restore_error_handler();
            }
        } else {
            $this->expectException(\TypeError::class);
            $this->expectExceptionMessage('cannot be called statically');
            $selector->select($class, 'foo()');
        }

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnNonStaticProperty(): void
    {
        $class = get_class(new class() {
            /** @var string */
            public $foo = 'FOO';
        });
        $selector = new ClassPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('undeclared static property');

        $selector->select($class, 'foo');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    // @codeCoverageIgnoreStart
    /**
     * @psalm-return iterable<string, array{
     *      key: string,
     *      method: string
     * }>
     */
    public static function provSelectThrowsOnNonClass(): iterable
    {
        // #0
        yield basename(__FILE__).':'.__LINE__ => [
            'key'    => 'foo',
            'method' => 'selectWithAttribute',
        ];

        // #1
        yield basename(__FILE__).':'.__LINE__ => [
            'key'    => 'foo()',
            'method' => 'selectWithMethod',
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provSelectThrowsOnNonClass
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnNonClass(string $key): void
    {
        $selector = new ClassPropertySelector();

        $message = sprintf(
            'Argument 1 passed to %s::select() must be a class, %s given',
            AbstractValueSelector::class,
            gettype(123)
        );
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        $selector->select(123, $key);

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

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
