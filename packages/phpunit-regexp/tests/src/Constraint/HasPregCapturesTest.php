<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Constraint\HasPregCaptures
 * @covers \Tailors\PHPUnit\Constraint\HasPregCapturesTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class HasPregCapturesTest extends TestCase
{
    use ProvHasPregCapturesTrait;

    /**
     * @dataProvider provHasPregCaptures
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
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
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testHasPregCapturesFails(array $expect, $actual, string $message): void
    {
        $constraint = HasPregCaptures::create($expect);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $this->assertNull($constraint->evaluate($actual));
    }

    /**
     * @dataProvider provNotHasPregCaptures
     * @dataProvider provNotHasPregCapturesNonArray
     *
     * @param mixed $actual
     *
     * @psalm-suppress MissingThrowsDocblock
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
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotHasPregCapturesFails(array $expect, $actual, string $message): void
    {
        $constraint = new LogicalNot(HasPregCaptures::create($expect));

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        $this->assertNull($constraint->evaluate($actual));
    }

    /**
     * @psalm-return iterable<string, array{
     *      args: list{array},
     *      message: string
     * }>
     */
    public static function provCreateThrowsInvalidArgumentException(): iterable
    {
        $template = 'Argument 1 passed to '.HasPregCaptures::class.'::create() '.
            'must be an array of valid expectations, '.
            'invalid %s at %s given.';

        yield basename(__FILE__).':'.__LINE__ => [
            'args'    => [[
                'foo' => new \stdClass(),
            ]],
            'message' => sprintf($template, 'expectation', 'key \'foo\''),
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'args'    => [[
                0 => 123.456,
                1 => false,
                2 => ['', 1, ''],
                3 => ['', 1],
                4 => [false, 1],
                5 => ['', 123.456],
            ]],
            'message' => sprintf($template, 'expectations', 'keys 0, 2, 4, 5'),
        ];
    }

    /**
     * @dataProvider provCreateThrowsInvalidArgumentException
     *
     * @psalm-param list{array} $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCreateThrowsInvalidArgumentException(array $args, string $message): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        HasPregCaptures::create(...$args);
    }
}

// vim: syntax=php sw=4 ts=4 et:
