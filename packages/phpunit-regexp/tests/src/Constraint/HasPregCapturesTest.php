<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(HasPregCaptures::class)]
#[CoversClass(ProvHasPregCapturesTrait::class)]
final class HasPregCapturesTest extends TestCase
{
    use ProvHasPregCapturesTrait;

    /**
     * @param mixed $actual
     */
    #[DataProvider('provHasPregCaptures')]
    public function testHasPregCapturesSucceeds(array $expect, $actual, string $message): void
    {
        $constraint = HasPregCaptures::create($expect);
        self::assertThat($actual, $constraint);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provNotHasPregCaptures')]
    #[DataProvider('provNotHasPregCapturesNonArray')]
    public function testHasPregCapturesFails(array $expect, $actual, string $message): void
    {
        $constraint = HasPregCaptures::create($expect);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $constraint->evaluate($actual);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provNotHasPregCaptures')]
    #[DataProvider('provNotHasPregCapturesNonArray')]
    public function testNotHasPregCapturesSucceeds(array $expect, $actual, string $message): void
    {
        $constraint = new LogicalNot(HasPregCaptures::create($expect));
        self::assertThat($actual, $constraint);
    }

    /**
     * @param mixed $actual
     */
    #[DataProvider('provHasPregCaptures')]
    public function testNotHasPregCapturesFails(array $expect, $actual, string $message): void
    {
        $constraint = new LogicalNot(HasPregCaptures::create($expect));

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $constraint->evaluate($actual);
    }

    public static function provCreateThrowsInvalidArgumentException(): array
    {
        $template = 'Argument 1 passed to '.HasPregCaptures::class.'::create() '.
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

    #[DataProvider('provCreateThrowsInvalidArgumentException')]
    public function testCreateThrowsInvalidArgumentException(array $args, string $message): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        HasPregCaptures::create(...$args);
    }
}

// vim: syntax=php sw=4 ts=4 et:
