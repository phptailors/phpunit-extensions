<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 * @covers \Tailors\PHPUnit\Constraint\HasPregCaptures
 * @covers \Tailors\PHPUnit\Constraint\ProvHasPregCapturesTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class HasPregCapturesTest extends TestCase
{
    use ProvHasPregCapturesTrait;

    /**
     * @dataProvider provHasPregCaptures
     *
     * @param mixed $actual
     */
    public function testHasPregCapturesSucceeds(array $expect, $actual): void
    {
        $constraint = HasPregCaptures::create($expect);
        self::assertThat($actual, $constraint);
    }

    /**
     * @dataProvider provNotHasPregCaptures
     * @dataProvider provNotHasPregCapturesNonArray
     *
     * @param mixed $actual
     */
    public function testHasPregCapturesFails(array $expected, $actual, string $message): void
    {
        $constraint = HasPregCaptures::create($expected);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $constraint->evaluate($actual);
    }

    /**
     * @dataProvider provNotHasPregCaptures
     * @dataProvider provNotHasPregCapturesNonArray
     *
     * @param mixed $actual
     */
    public function testNotHasPregCapturesSucceeds(array $expect, $actual): void
    {
        $constraint = new LogicalNot(HasPregCaptures::create($expect));
        self::assertThat($actual, $constraint);
    }

    /**
     * @dataProvider provHasPregCaptures
     *
     * @param mixed $actual
     */
    public function testNotHasPregCapturesFails(array $expect, $actual, string $message): void
    {
        $constraint = new LogicalNot(HasPregCaptures::create($expect));

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $constraint->evaluate($actual);
    }

    public static function provCreateThrowsInvalidArgumentException(): array
    {
        $template = 'Argument #1 of '.HasPregCaptures::class.'::create() '.
            'must be an array of valid expectations, '.
            'invalid %s at %s given.';

        return [
            'HasPregCapturesTest.php:'.__LINE__ => [
                'args' => [[
                    'foo' => new \stdClass(),
                ]],
                'message' => sprintf($template, 'expectation', 'key \'foo\''),
            ],

            'HasPregCapturesTest.php:'.__LINE__ => [
                'args' => [[
                    0 => 123.456,
                    1 => false,
                    2 => ['', 1, ''],
                    3 => ['', 1],
                    4 => [false, 1],
                    5 => ['', 123.456],
                ]],
                'message' => sprintf($template, 'expectations', 'keys 0, 2, 4, 5'),
            ],
        ];
    }

    /**
     * @dataProvider provCreateThrowsInvalidArgumentException
     */
    public function testCreateThrowsInvalidArgumentException(array $args, string $message): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        HasPregCaptures::create(...$args);
    }
}

// vim: syntax=php sw=4 ts=4 et:
