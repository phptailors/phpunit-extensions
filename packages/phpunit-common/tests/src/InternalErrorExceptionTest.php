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
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(InternalErrorException::class)]
#[Small]
final class InternalErrorExceptionTest extends TestCase
{
    public function testFromBackTrace(): void
    {
        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
        $caller = end($stack);

        $exception = InternalErrorException::fromBackTrace('bad dog');
        self::assertSame('bad dog', $exception->getMessage());
        self::assertSame($caller['file'], $exception->getFile());
        self::assertSame($caller['line'], $exception->getLine());
    }
}

// vim: syntax=php sw=4 ts=4 et:
