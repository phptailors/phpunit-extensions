<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ExtendsClass;
use Tailors\PHPUnit\Examples\Inheritance\ExampleTrait;

/**
 * @small
 * @covers \Tailors\PHPUnit\ExtendsClassTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class ExtendsClassTraitTest extends TestCase
{
    use ExtendsClassTrait;

    public static function provExtendsClass(): array
    {
        $template = 'Failed asserting that %s does not extend class %s.';

        return [
            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'class'   => \Exception::class,
                'subject' => \ErrorException::class,
                'message' => sprintf($template, \ErrorException::class, \Exception::class),
            ],

            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'class'   => \Exception::class,
                'subject' => new \ErrorException(),
                'message' => sprintf($template, 'object '.\ErrorException::class, \Exception::class),
            ],
        ];
    }

    public static function provNotExtendsClass(): array
    {
        $template = 'Failed asserting that %s extends class %s.';

        return [
            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'class'   => \Error::class,
                'subject' => \ErrorException::class,
                'message' => sprintf($template, \ErrorException::class, \Error::class),
            ],
            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'class'   => \Error::class,
                'subject' => new \ErrorException(),
                'message' => sprintf($template, 'object '.\ErrorException::class, \Error::class),
            ],
            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'class'   => \Error::class,
                'subject' => 'lorem ipsum',
                'message' => sprintf($template, "'lorem ipsum'", \Error::class),
            ],
            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'class'   => \Error::class,
                'subject' => 123,
                'message' => sprintf($template, '123', \Error::class),
            ],
        ];
    }

    /**
     * @dataProvider provExtendsClass
     *
     * @param mixed $subject
     */
    public function testAssertExtendsClassSucceeds(string $class, $subject): void
    {
        self::assertExtendsClass($class, $subject);
    }

    /**
     * @dataProvider provNotExtendsClass
     *
     * @param mixed $subject
     */
    public function testAssertExtendsClassFails(string $class, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertExtendsClass($class, $subject);
    }

    /**
     * @dataProvider provNotExtendsClass
     *
     * @param mixed $subject
     */
    public function testAssertNotExtendsClassSucceeds(string $class, $subject): void
    {
        self::assertNotExtendsClass($class, $subject);
    }

    /**
     * @dataProvider provExtendsClass
     *
     * @param mixed $subject
     */
    public function testAssertNotExtendsClassFails(string $class, $subject, string $message): void
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertNotExtendsClass($class, $subject);
    }

    /**
     * @dataProvider provExtendsClass
     *
     * @param mixed $subject
     */
    public function testExtendsClass(string $class, $subject): void
    {
        self::assertThat($subject, self::extendsClass($class));
    }

    /**
     * @dataProvider provNotExtendsClass
     *
     * @param mixed $subject
     */
    public function testNotExtendsClass(string $class, $subject): void
    {
        self::assertThat($subject, self::logicalNot(self::extendsClass($class)));
    }

    public static function provExtendsClassThrowsInvalidArgumentException(): array
    {
        $template = 'Argument #1 of %s::create() must be a class-string';

        return [
            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'argument' => 'non-class string',
                'message'  => sprintf($template, ExtendsClass::class),
            ],

            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'argument' => \Throwable::class,
                'message'  => sprintf($template, ExtendsClass::class),
            ],

            'ExtendsClassTraitTest.php:'.__LINE__ => [
                'argument' => ExampleTrait::class,
                'message'  => sprintf($template, ExtendsClass::class),
            ],
        ];
    }

    /**
     * @dataProvider provExtendsClassThrowsInvalidArgumentException
     */
    public function testExtendsClassThrowsInvalidArgumentException(string $argument, string $message): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage($message);

        self::extendsClass($argument);
    }
}

// vim: syntax=php sw=4 ts=4 et:
