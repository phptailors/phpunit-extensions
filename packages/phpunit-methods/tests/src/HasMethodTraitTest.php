<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ProvHasMethodTrait;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\HasMethodTrait
 * @covers \Tailors\PHPUnit\HasMethodTraitTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class HasMethodTraitTest extends TestCase
{
    use HasMethodTrait;
    use ProvHasMethodTrait;

    /**
     * @psalm-return iterable<string, array{method: string, subject: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provHasMethodSucceeds(): iterable
    {
        foreach (self::provHasMethod() as $key => $case) {
            yield $key => [
                'method'  => $case['method'],
                'subject' => $case['subject'],
            ];
        }
    }

    /**
     * @psalm-return iterable<string, array{method: string, subject: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provNotHasMethodSucceeds(): iterable
    {
        foreach (self::provNotHasMethod() as $key => $case) {
            yield $key => [
                'method'  => $case['method'],
                'subject' => $case['subject'],
            ];
        }
    }

    /**
     * @dataProvider provHasMethodSucceeds
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testHasMethodSucceeds(string $method, $subject): void
    {
        self::assertThat($subject, self::hasMethod($method));
    }

    /**
     * @dataProvider provHasMethodSucceeds
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertHasMethodSucceeds(string $method, $subject): void
    {
        self::assertHasMethod($method, $subject);
    }

    /**
     * @dataProvider provNotHasMethodSucceeds
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testNotHasMethodSucceeds(string $method, $subject): void
    {
        self::assertThat($subject, self::logicalNot(self::hasMethod($method)));
    }

    /**
     * @dataProvider provNotHasMethodSucceeds
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotHasMethodSucceeds(string $method, $subject): void
    {
        self::assertNotHasMethod($method, $subject);
    }

    /**
     * @dataProvider provNotHasMethod
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertHasMethodFails(string $method, $subject, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        self::assertHasMethod($method, $subject);
    }

    /**
     * @dataProvider provHasMethod
     *
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testAssertNotHasMethodFails(string $method, $subject, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage($message);

        self::assertNotHasMethod($method, $subject);
    }
}

// vim: syntax=php sw=4 ts=4 et:
