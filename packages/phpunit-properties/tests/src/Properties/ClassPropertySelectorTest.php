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
 * @covers \Tailors\PHPUnit\Properties\ClassPropertySelector
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class ClassPropertySelectorTest extends TestCase
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
        self::assertImplementsInterface(PropertySelectorInterface::class, ClassPropertySelector::class);
    }

    public function testExtendsAbstractPropertySelector(): void
    {
        self::assertExtendsClass(AbstractPropertySelector::class, ClassPropertySelector::class);
    }

    //
    // canSelectFrom()
    //

    // @codeCoverageIgnoreStart
    public static function provCanSelectFrom(): array
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
                'subject' => get_class(new class() {
                }),
                'expect' => true,
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
     * @dataProvider provCanSelectFrom
     *
     * @param mixed $subject
     */
    public function testCanSelectFrom($subject, bool $expect): void
    {
        $selector = new ClassPropertySelector();
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
     * @dataProvider provSelectProperty
     *
     * @param mixed $key
     * @param mixed $return
     * @param mixed $expect
     */
    public function testSelectProperty(string $class, $key, $return, $expect): void
    {
        $selector = new ClassPropertySelector();
        self::assertSame($return, $selector->selectProperty($class, $key, $retval));
        self::assertSame($expect, $retval);
    }

    public function testSelectPropertyThrowsOnPrivateMethod(): void
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

        $selector->selectProperty($class, 'foo()');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testSelectPropertyThrowsOnPrivateAttribute(): void
    {
        $class = get_class(new class() {
            private $foo = 'FOO';
        });
        $selector = new ClassPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('private property');

        $selector->selectProperty($class, 'foo');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testSelectPropertyThrowsOnNonStaticMethod(): void
    {
        $class = get_class(new class() {
            public function foo()
            {
                // @codeCoverageIgnoreStart
            }

            // @codeCoverageIgnoreEnd
        });
        $selector = new ClassPropertySelector();

        if (PHP_VERSION_ID < 80000) {
            $this->expectError();
            $this->expectErrorMessage('should not be called statically');
        } else {
            $this->expectException(\TypeError::class);
            $this->expectExceptionMessage('cannot be called statically');
        }

        $selector->selectProperty($class, 'foo()');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testSelectPropertyThrowsOnNonStaticProperty(): void
    {
        $class = get_class(new class() {
            public $foo = 'FOO';
        });
        $selector = new ClassPropertySelector();

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('undeclared static property');

        $selector->selectProperty($class, 'foo');

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    // @codeCoverageIgnoreStart
    public static function provSelectPropertyThrowsOnNonClass(): array
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
     * @dataProvider provSelectPropertyThrowsOnNonClass
     */
    public function testSelectPropertyThrowsOnNonClass(string $key, string $method): void
    {
        $selector = new ClassPropertySelector();

        $method = preg_quote(ClassPropertySelector::class.'::'.$method.'()', '/');
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Argument #1 of '.$method.' must be a class/');

        $selector->selectProperty(123, $key);

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    public function testSubject(): void
    {
        $selector = new ClassPropertySelector();
        self::assertSame('a class', $selector->subject());
    }
}
// vim: syntax=php sw=4 ts=4 et:
