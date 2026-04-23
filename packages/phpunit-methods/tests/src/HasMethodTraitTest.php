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
     * @dataProvider provHasMethodSucceeds
     *
     * @psalm-param non-empty-string $method
     */
    public function testHasMethodSucceeds(string $method, mixed $subject)
    {
        self::assertThat($subject, self::hasMethod($method));
    }

    /**
     * @dataProvider provHasMethodSucceeds
     *
     * @psalm-param non-empty-string $method
     */
    public function testAssertHasMethodSucceeds(string $method, mixed $subject)
    {
        self::assertHasMethod($method, $subject);
    }

    /**
     * @dataProvider provHasMethodFails
     *
     * @psalm-param non-empty-string $method
     */
    public function testAssertHasMethodFails(string $method, mixed $subject, string $message)
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertHasMethod($method, $subject);
    }

    /**
     * @dataProvider provHasMethodFails
     *
     * @psalm-param non-empty-string $method
     */
    public function testNotHasMethodSucceeds(string $method, mixed $subject)
    {
        self::assertThat($method, self::logicalNot(self::hasMethod($method)));
    }

    /**
     * @dataProvider provHasMethodFails
     *
     * @psalm-param non-empty-string $method
     */
    public function testAssertNotHasMethodSucceeds(string $method, mixed $subject)
    {
        self::assertNotHasMethod($method, $subject);
    }

    /**
     * @dataProvider provHasMethodSucceeds
     *
     * @psalm-param non-empty-string $method
     */
    public function testAssertNotHasMethodFails(string $method, mixed $subject, string $message)
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertNotHasMethod($method, $subject);
    }
}

// vim: syntax=php sw=4 ts=4 et:
