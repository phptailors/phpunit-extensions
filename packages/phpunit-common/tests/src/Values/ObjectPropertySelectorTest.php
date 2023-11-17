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

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\AbstractPropertySelector
 * @covers \Tailors\PHPUnit\Values\AbstractValueSelector
 * @covers \Tailors\PHPUnit\Values\ObjectPropertySelector
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ObjectPropertySelectorTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function testImplementsValueSelectorInterface(): void
    {
        self::assertInstanceOf(ValueSelectorInterface::class, new ObjectPropertySelector());
    }

    public function testExtendsAbstractPropertySelector(): void
    {
        self::assertInstanceOf(AbstractPropertySelector::class, new ObjectPropertySelector());
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
                'expect'  => false,
            ],

            // #2
            'object' => [
                'subject' => new class() {},
                'expect'  => true,
            ],

            // #3
            'new ObjectPropertySelector' => [
                'subject' => new ObjectPropertySelector(),
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
        $selector = new ObjectPropertySelector();
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
                'object' => new class() {
                    public $foo = 'FOO';
                },
                'key'    => 'foo',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #1
            [
                'object' => new class() {
                    public $foo = 'FOO';
                },
                'key'    => 'bar',
                'return' => false,
                'expect' => null,
            ],

            // #2
            [
                'object' => new class() {
                    public function foo()
                    {
                        return 'FOO';
                    }
                },
                'key'    => 'foo()',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #3
            [
                'object' => new class() {
                    public static function foo()
                    {
                        return 'FOO';
                    }
                },
                'key'    => 'foo()',
                'return' => true,
                'expect' => 'FOO',
            ],

            // #4
            [
                'object' => new class() {
                    public function foo()
                    {
                        return 'FOO';
                    }
                },
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
    public function testSelect(object $object, $key, $return, $expect): void
    {
        $selector = new ObjectPropertySelector();
        self::assertSame($return, $selector->select($object, $key, $retval));
        self::assertSame($expect, $retval);
    }

    public function testSelectThrowsOnPrivateMethod(): void
    {
        $object = new class() {
            private function foo()
            {
                // @codeCoverageIgnoreStart
            }

            // @codeCoverageIgnoreEnd
        };
        $selector = new ObjectPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private method');

        $selector->select($object, 'foo()');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testSelectThrowsOnPrivateAttribute(): void
    {
        $object = new class() {
            private $foo = 'FOO';
        };
        $selector = new ObjectPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private property');

        $selector->select($object, 'foo');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testSelectThrowsOnStaticProperty(): void
    {
        $object = new class() {
            public static $foo = 'FOO';
        };
        $selector = new ObjectPropertySelector();

        // Because expectError() is removed in phpunit 10.
        try {
            set_error_handler(static function (int $severity, string $message): void {
                throw new \ErrorException($message, $severity);
            });
            $this->expectException(\ErrorException::class);
            $this->expectExceptionMessage('static property');

            $selector->select($object, 'foo');
        } finally {
            restore_error_handler();
        }

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    // @codeCoverageIgnoreStart
    public static function provSelectThrowsOnNonobject(): array
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
     * @dataProvider provSelectThrowsOnNonobject
     */
    public function testSelectThrowsOnNonobject(string $key, string $method): void
    {
        $selector = new ObjectPropertySelector();

        $message = sprintf(
            'Argument 1 passed to %s::select() must be an object, %s given',
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
        $selector = new ObjectPropertySelector();
        self::assertSame('an object', $selector->subject());
    }

    public function testSelectable(): void
    {
        $selector = new ObjectPropertySelector();
        self::assertSame('properties', $selector->selectable());
    }
}
// vim: syntax=php sw=4 ts=4 et:
