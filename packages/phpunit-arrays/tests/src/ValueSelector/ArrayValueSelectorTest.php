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
#[CoversClass(ArrayValueSelector::class)]
#[CoversClass(ArrayValueSelectorTest::class)]
#[Small]
final class ArrayValueSelectorTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsValueSelectorInterface(): void
    {
        self::assertInstanceOf(ValueSelectorInterface::class, new ArrayValueSelector());
    }

    /**
     * @psalm-return iterable<string, array{
     *      subject: mixed,
     *      expect: bool
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
            'subject' => 123,
            'expect'  => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => null,
            'expect'  => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => self::class,
            'expect'  => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => new class() {},
            'expect'  => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => new ArrayValueSelector(),
            'expect'  => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => [],
            'expect'  => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => new \ArrayObject(),
            'expect'  => true,
        ];
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provSupports')]
    public function testSupports($subject, bool $expect): void
    {
        $selector = new ArrayValueSelector();
        self::assertSame($expect, $selector->supports($subject));
    }

    /**
     * @psalm-return iterable<string, array{
     *      subject: array|\ArrayAccess,
     *      key: array-key,
     *      return: mixed,
     *      expect: mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provSelect(): iterable
    {
        $arrayAccessFoo = new
        /**
         * @template-implements \ArrayAccess<array-key, mixed>
         */
        readonly class() implements \ArrayAccess {
            private string $foo;

            public function __construct()
            {
                $this->foo = 'FOO';
            }

            public function offsetExists($offset): bool
            {
                return 'foo' === $offset;
            }

            #[\ReturnTypeWillChange]
            public function offsetGet($offset)
            {
                return $this->foo;
            }

            public function offsetSet($offset, $value): void {}

            public function offsetUnset($offset): void {}
        };

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => [
                'foo' => 'FOO',
            ],
            'key'     => 'foo',
            'return'  => true,
            'expect'  => 'FOO',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => [
                'foo' => 'FOO',
            ],
            'key'     => 'bar',
            'return'  => false,
            'expect'  => null,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => new \ArrayObject([
                'foo' => 'FOO',
            ]),
            'key'     => 'foo',
            'return'  => true,
            'expect'  => 'FOO',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => new \ArrayObject([
                'foo' => 'FOO',
            ]),
            'key'     => 'bar',
            'return'  => false,
            'expect'  => null,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => $arrayAccessFoo,
            'key'     => 'foo',
            'return'  => true,
            'expect'  => 'FOO',
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'subject' => $arrayAccessFoo,
            'key'     => 'bar',
            'return'  => false,
            'expect'  => null,
        ];
    }

    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $return
     * @param mixed $expect
     *
     * @psalm-param array|\ArrayAccess $subject
     * @psalm-param array-key          $key
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provSelect')]
    public function testSelect($subject, $key, $return, $expect): void
    {
        $selector = new ArrayValueSelector();
        self::assertSame($return, $selector->select($subject, $key, $retval));
        self::assertSame($expect, $retval);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectThrowsOnNonArray(): void
    {
        $selector = new ArrayValueSelector();

        $message = sprintf(
            'Argument 1 passed to %s::select() must be an array or ArrayAccess, %s given',
            AbstractValueSelector::class,
            gettype(123)
        );
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage($message);

        $selector->select(123, 'foo');
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSubject(): void
    {
        $selector = new ArrayValueSelector();
        self::assertSame('an array or ArrayAccess', $selector->subject());
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testSelectable(): void
    {
        $selector = new ArrayValueSelector();
        self::assertSame('values', $selector->selectable());
    }
}
// vim: syntax=php sw=4 ts=4 et:
