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
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ProvHasMethodTrait;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(HasMethodTrait::class)]
final class HasMethodTraitTest extends TestCase
{
    use HasMethodTrait;
    use ProvHasMethodTrait;

    /**
     * @param mixed $subject
     *
     * @psalm-param non-empty-string $method
     */
    #[DataProvider('provHasMethodSucceeds')]
    public function testHasMethodSucceeds(string $method, $subject)
    {
        self::assertThat($subject, self::hasMethod($method));
    }

    /**
     * @param mixed $subject
     *
     * @psalm-param non-empty-string $method
     */
    #[DataProvider('provHasMethodSucceeds')]
    public function testAssertHasMethodSucceeds(string $method, $subject)
    {
        self::assertHasMethod($method, $subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-param non-empty-string $method
     */
    #[DataProvider('provHasMethodFails')]
    public function testAssertHasMethodFails(string $method, $subject, string $message)
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertHasMethod($method, $subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-param non-empty-string $method
     */
    #[DataProvider('provHasMethodFails')]
    public function testNotHasMethodSucceeds(string $method, $subject)
    {
        self::assertThat($method, self::logicalNot(self::hasMethod($method)));
    }

    /**
     * @param mixed $subject
     *
     * @psalm-param non-empty-string $method
     */
    #[DataProvider('provHasMethodFails')]
    public function testAssertNotHasMethodSucceeds(string $method, $subject)
    {
        self::assertNotHasMethod($method, $subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-param non-empty-string $method
     */
    #[DataProvider('provHasMethodSucceeds')]
    public function testAssertNotHasMethodFails(string $method, $subject, string $message)
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertNotHasMethod($method, $subject);
    }
}

// vim: syntax=php sw=4 ts=4 et:
