<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\InvalidArgumentException;

final class ClassWithNonStaticMethodFooBLSGG
{
    public function foo()
    {
        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd
}

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\AbstractPropertySelector
 * @covers \Tailors\PHPUnit\Values\AbstractValueSelector
 * @covers \Tailors\PHPUnit\Values\ClassPropertySelector
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ClassPropertySelectorTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function testImplementsValueSelectorInterface(): void
    {
        self::assertInstanceOf(ValueSelectorInterface::class, new ClassPropertySelector());
    }

    public function testExtendsAbstractPropertySelector(): void
    {
        self::assertInstanceOf(AbstractPropertySelector::class, new ClassPropertySelector());
    }

    //
    // supports()
    //

    // @codeCoverageIgnoreStart
    public static function provSupports(): array
    {
        return [
            // #0
            'string' => [
                'subject' => 'foo',
                'expect'  => false,
            ],

            // #1
            'array' => [
                'subject' => [],
                'expect'  => false,
            ],

            'class' => [
                'subject' => self::class,
                'expect'  => true,
            ],

            // #2
            'object' => [
                'subject' => get_class(new class() {}),
                'expect'  => true,
            ],

            // #3
            'new ClassPropertySelector' => [
                'subject' => ClassPropertySelector::class,
                'expect'  => true,
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provSupports
     *
     * @param mixed $subject
     */
    public function testSupports($subject, bool $expect): void
    {
        $selector = new ClassPropertySelector();
        self::assertSame($expect, $selector->supports($subject));
    }

    //
    // select
    //

    // @codeCoverageIgnoreStart
    public static function provSelect(): array
    {
        return [
            // #0
            [
                'class' => get_class(new class() {
                    public static $foo = 'FOO';
                }),
                'key'    => 'foo',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #1
            [
                'class' => get_class(new class() {
                    public static $foo = 'FOO';
                }),
                'key'    => 'bar',
                'return' => false,
                'expect' => null,
            ],

            // #2
            [
                'class' => get_class(new class() {
                    public static function foo()
                    {
                        return 'FOO';
                    }
                }),
                'key'    => 'foo()',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #3
            [
                'class' => get_class(new class() {
                    public static function foo()
                    {
                        return 'FOO';
                    }
                }),
                'key'    => 'bar()',
                'return' => false,
                'expect' => null,
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provSelect
     *
     * @param mixed $key
     * @param mixed $return
     * @param mixed $expect
     */
    public function testSelect(string $class, $key, $return, $expect): void
    {
        $selector = new ClassPropertySelector();
        self::assertSame($return, $selector->select($class, $key, $retval));
        self::assertSame($expect, $retval);
    }

    public function testSelectThrowsOnPrivateMethod(): void
    {
        $class = get_class(new class() {
            private static function foo()
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

    public function testSelectThrowsOnPrivateAttribute(): void
    {
        $class = get_class(new class() {
            private $foo = 'FOO';
        });
        $selector = new ClassPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private property');

        $selector->select($class, 'foo');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

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

    public function testSelectThrowsOnNonStaticProperty(): void
    {
        $class = get_class(new class() {
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
    public static function provSelectThrowsOnNonClass(): array
    {
        return [
            // #0
            [
                'key'    => 'foo',
                'method' => 'selectWithAttribute',
            ],

            // #1
            [
                'key'    => 'foo()',
                'method' => 'selectWithMethod',
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provSelectThrowsOnNonClass
     */
    public function testSelectThrowsOnNonClass(string $key, string $method): void
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

    public function testSubject(): void
    {
        $selector = new ClassPropertySelector();
        self::assertSame('a class', $selector->subject());
    }

    public function testSelectable(): void
    {
        $selector = new ClassPropertySelector();
        self::assertSame('properties', $selector->selectable());
    }
}
// vim: syntax=php sw=4 ts=4 et:
