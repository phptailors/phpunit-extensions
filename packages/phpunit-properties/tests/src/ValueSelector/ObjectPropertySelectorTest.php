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
 * @covers \Tailors\PHPUnit\ValueSelector\ObjectPropertySelector
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ObjectPropertySelectorTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsValueSelectorInterface(): void
    {
        self::assertInstanceOf(ValueSelectorInterface::class, new ObjectPropertySelector());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractPropertySelector(): void
    {
        self::assertInstanceOf(AbstractPropertySelector::class, new ObjectPropertySelector());
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
            'expect'  => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => new class() {},
            'expect'  => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => new ObjectPropertySelector(),
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
        $selector = new ObjectPropertySelector();
        self::assertSame($expect, $selector->supports($subject));
    }

    // @codeCoverageIgnoreStart
    /**
     * @psalm-return iterable<string, array{
     *      object: mixed,
     *      key: array-key,
     *      return:  mixed,
     *      expect: mixed
     * }>
     */
    public static function provSelect(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'object' => new class() {
                /** @var string */
                public $foo = 'FOO';
            },
            'key'    => 'foo',
            'return' => true,
            'expect' => 'FOO',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'object' => new class() {
                /** @var string */
                public $foo = 'FOO';
            },
            'key'    => 'bar',
            'return' => false,
            'expect' => null,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'object' => new class() {
                public function foo(): string
                {
                    return 'FOO';
                }
            },
            'key'    => 'foo()',
            'return' => true,
            'expect' => 'FOO',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'object' => new class() {
                public static function foo(): string
                {
                    return 'FOO';
                }
            },
            'key'    => 'foo()',
            'return' => true,
            'expect' => 'FOO',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'object' => new class() {
                public function foo(): string
                {
                    return 'FOO';
                }
            },
            'key'    => 'bar()',
            'return' => false,
            'expect' => null,
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provSelect
     *
     * @param mixed $object
     * @param mixed $key
     * @param mixed $return
     * @param mixed $expect
     *
     * @psalm-param array-key $key
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelect($object, $key, $return, $expect): void
    {
        $selector = new ObjectPropertySelector();
        self::assertSame($return, $selector->select($object, $key, $retval));
        self::assertSame($expect, $retval);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnPrivateMethod(): void
    {
        $object = new class() {
            private function foo(): void
            {
                // @codeCoverageIgnoreStart
            }

            // @codeCoverageIgnoreEnd
        };
        $selector = new ObjectPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private method');

        /** @psalm-suppress RedundantCondition */
        $selector->select($object, 'foo()');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnPrivateAttribute(): void
    {
        $object = new class() {
            /** @var string */
            private $foo = 'FOO';
        };
        $selector = new ObjectPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private property');

        /** @psalm-suppress RedundantCondition */
        $selector->select($object, 'foo');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnStaticProperty(): void
    {
        $object = new class() {
            /** @var string */
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

            /** @psalm-suppress RedundantCondition */
            $selector->select($object, 'foo');
        } finally {
            restore_error_handler();
        }

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
    public static function provSelectThrowsOnNonobject(): iterable
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
     * @dataProvider provSelectThrowsOnNonobject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnNonobject(string $key): void
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

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSubject(): void
    {
        $selector = new ObjectPropertySelector();
        self::assertSame('an object', $selector->subject());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectable(): void
    {
        $selector = new ObjectPropertySelector();
        self::assertSame('properties', $selector->selectable());
    }
}
// vim: syntax=php sw=4 ts=4 et:
