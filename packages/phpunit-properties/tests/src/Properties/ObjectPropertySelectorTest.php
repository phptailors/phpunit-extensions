<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 * @covers \Tailors\PHPUnit\Properties\AbstractPropertySelector
 * @covers \Tailors\PHPUnit\Properties\ObjectPropertySelector
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class ObjectPropertySelectorTest extends TestCase
{
    use ImplementsInterfaceTrait;
    use ExtendsClassTrait;

    //
    //
    // TESTS
    //
    //

    public function testImplementsPropertySelectorInterface(): void
    {
        self::assertImplementsInterface(PropertySelectorInterface::class, ObjectPropertySelector::class);
    }

    public function testExtendsAbstractPropertySelector(): void
    {
        self::assertExtendsClass(AbstractPropertySelector::class, ObjectPropertySelector::class);
    }

    //
    // canSelectFrom()
    //

    // @codeCoverageIgnoreStart
    public function provCanSelectFrom(): array
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
                'subject' => new class() {
                },
                'expect' => true,
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
     * @dataProvider provCanSelectFrom
     *
     * @param mixed $subject
     */
    public function testCanSelectFrom($subject, bool $expect): void
    {
        $selector = new ObjectPropertySelector();
        self::assertSame($expect, $selector->canSelectFrom($subject));
    }

    //
    // selectProperty
    //

    // @codeCoverageIgnoreStart
    public static function provSelectProperty(): array
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
     * @dataProvider provSelectProperty
     *
     * @param mixed $key
     * @param mixed $return
     * @param mixed $expect
     */
    public function testSelectProperty(object $object, $key, $return, $expect): void
    {
        $selector = new ObjectPropertySelector();
        self::assertSame($return, $selector->selectProperty($object, $key, $retval));
        self::assertSame($expect, $retval);
    }

    public function testSelectPropertyThrowsOnPrivateMethod(): void
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

        $selector->selectProperty($object, 'foo()');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testSelectPropertyThrowsOnPrivateAttribute(): void
    {
        $object = new class() {
            private $foo = 'FOO';
        };
        $selector = new ObjectPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private property');

        $selector->selectProperty($object, 'foo');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testSelectPropertyThrowsOnStaticProperty(): void
    {
        $object = new class() {
            public static $foo = 'FOO';
        };
        $selector = new ObjectPropertySelector();

        $this->expectError();
        $this->expectErrorMessage('static property');

        $selector->selectProperty($object, 'foo');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    // @codeCoverageIgnoreStart
    public static function provSelectPropertyThrowsOnNonobject(): array
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
     * @dataProvider provSelectPropertyThrowsOnNonobject
     */
    public function testSelectPropertyThrowsOnNonobject(string $key, string $method): void
    {
        $selector = new ObjectPropertySelector();

        $method = preg_quote(ObjectPropertySelector::class.'::'.$method.'()', '/');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Argument 1 passed to '.$method.' must be an object/');

        $selector->selectProperty(123, $key);

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testSubject(): void
    {
        $selector = new ObjectPropertySelector();
        self::assertSame('an object', $selector->subject());
    }
}
// vim: syntax=php sw=4 ts=4 et:
