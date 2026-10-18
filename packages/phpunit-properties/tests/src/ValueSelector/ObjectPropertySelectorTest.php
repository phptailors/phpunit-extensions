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
use Tailors\PHPUnit\Polyfill\ExpectExceptionMessageIsOrContainsTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(ObjectPropertySelector::class)]
#[CoversClass(ObjectPropertySelectorTest::class)]
#[Small]
final class ObjectPropertySelectorTest extends TestCase
{
    use ExpectExceptionMessageIsOrContainsTrait;

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

    /**
     * @param mixed $subject
     * @param mixed $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provSupports')]
    public function testSupports($subject, $expect): void
    {
        $selector = new ObjectPropertySelector();
        self::assertSame($expect, $selector->supports($subject));
    }

    /**
     * @psalm-return iterable<string, array{
     *      object: mixed,
     *      key: array-key,
     *      return:  mixed,
     *      expect: mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provSelect(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'object' => new class() {
                public string $foo = 'FOO';
            },
            'key'    => 'foo',
            'return' => true,
            'expect' => 'FOO',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'object' => new class() {
                public string $foo = 'FOO';
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

    /**
     * @param mixed $object
     * @param mixed $key
     * @param mixed $return
     * @param mixed $expect
     *
     * @psalm-param array-key $key
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provSelect')]
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
            private function foo(): void {}
        };
        $selector = new ObjectPropertySelector();

        self::expectException(\Error::class);
        self::expectExceptionMessageIsOrContains('private method');

        /** @psalm-suppress RedundantCondition */
        $selector->select($object, 'foo()');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnPrivateAttribute(): void
    {
        $object = new class() {
            private string $foo = 'FOO';
        };
        $selector = new ObjectPropertySelector();

        self::expectException(\Error::class);
        self::expectExceptionMessageIsOrContains('private property');

        /** @psalm-suppress RedundantCondition */
        $selector->select($object, 'foo');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnStaticProperty(): void
    {
        $object = new class() {
            public static string $foo = 'FOO';
        };
        $selector = new ObjectPropertySelector();

        // Because expectError() is removed in phpunit 10.
        try {
            set_error_handler(static function (int $severity, string $message): void {
                throw new \ErrorException($message, $severity);
            });
            self::expectException(\ErrorException::class);
            self::expectExceptionMessageIsOrContains('static property');

            /** @psalm-suppress RedundantCondition */
            $selector->select($object, 'foo');
        } finally {
            restore_error_handler();
        }
    }

    /**
     * @psalm-return iterable<string, array{key: string}>
     *
     * @codeCoverageIgnore
     */
    public static function provSelectThrowsOnNonobject(): iterable
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
    #[DataProvider('provSelectThrowsOnNonobject')]
    public function testSelectThrowsOnNonobject(string $key): void
    {
        $selector = new ObjectPropertySelector();

        $message = sprintf(
            'Argument 1 passed to %s::select() must be an object, %s given',
            AbstractValueSelector::class,
            gettype(123)
        );
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessageIsOrContains($message);

        $selector->select(123, $key);
    }

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
