<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Constraint\ProvHasMethodTrait;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversTrait(HasMethodTrait::class)]
#[Small]
final class HasMethodTraitTest extends TestCase
{
    use HasMethodTrait;
    use ProvHasMethodTrait;

    /**
     * @psalm-param non-empty-string $method
     */
    #[DataProvider('provHasMethodSucceeds')]
    public function testHasMethodSucceeds(string $method, mixed $subject, string $_)
    {
        self::assertThat($subject, self::hasMethod($method));
    }

    /**
     * @psalm-param non-empty-string $method
     */
    #[DataProvider('provHasMethodSucceeds')]
    public function testAssertHasMethodSucceeds(string $method, mixed $subject, string $_)
    {
        self::assertHasMethod($method, $subject);
    }

    /**
     * @psalm-param non-empty-string $method
     */
    #[DataProvider('provHasMethodFails')]
    public function testAssertHasMethodFails(string $method, mixed $subject, string $message)
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertHasMethod($method, $subject);
    }

    /**
     * @psalm-param non-empty-string $method
     */
    #[DataProvider('provHasMethodFails')]
    public function testNotHasMethodSucceeds(string $method, mixed $subject, string $_)
    {
        self::assertThat($method, self::logicalNot(self::hasMethod($method)));
    }

    /**
     * @psalm-param non-empty-string $method
     */
    #[DataProvider('provHasMethodFails')]
    public function testAssertNotHasMethodSucceeds(string $method, mixed $subject, string $_)
    {
        self::assertNotHasMethod($method, $subject);
    }

    /**
     * @psalm-param non-empty-string $method
     */
    #[DataProvider('provHasMethodSucceeds')]
    public function testAssertNotHasMethodFails(string $method, mixed $subject, string $message)
    {
        self::expectException(ExpectationFailedException::class);
        self::expectExceptionMessage($message);

        self::assertNotHasMethod($method, $subject);
    }
}

// vim: syntax=php sw=4 ts=4 et:
