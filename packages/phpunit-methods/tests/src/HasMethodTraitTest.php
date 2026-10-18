<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ProvHasMethodTrait;
use Tailors\PHPUnit\Polyfill\ExpectExceptionMessageIsOrContainsTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(HasMethodTraitTest::class)]
#[Small]
final class HasMethodTraitTest extends TestCase
{
    use ExpectExceptionMessageIsOrContainsTrait;
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
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provHasMethodSucceeds')]
    public function testHasMethodSucceeds(string $method, $subject): void
    {
        self::assertThat($subject, self::hasMethod($method));
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provHasMethodSucceeds')]
    public function testAssertHasMethodSucceeds(string $method, $subject): void
    {
        self::assertHasMethod($method, $subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotHasMethodSucceeds')]
    public function testNotHasMethodSucceeds(string $method, $subject): void
    {
        self::assertThat($subject, self::logicalNot(self::hasMethod($method)));
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotHasMethodSucceeds')]
    public function testAssertNotHasMethodSucceeds(string $method, $subject): void
    {
        self::assertNotHasMethod($method, $subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provNotHasMethod')]
    public function testAssertHasMethodFails(string $method, $subject, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageIsOrContains($message);

        self::assertHasMethod($method, $subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provHasMethod')]
    public function testAssertNotHasMethodFails(string $method, $subject, string $message): void
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageIsOrContains($message);

        self::assertNotHasMethod($method, $subject);
    }
}

// vim: syntax=php sw=4 ts=4 et:
