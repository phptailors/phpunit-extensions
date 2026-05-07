<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class InternalErrorException extends \LogicException implements ExceptionInterface
{
    public static function fromBackTrace(string $message, int $distance = 1): self
    {
        /** @psalm-var non-empty-array<array{file: string, line: int}> */
        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1 + $distance);

        $caller = end($stack);

        $exception = new self($message);

        $exception->file = $caller['file'];
        $exception->line = $caller['line'];

        return $exception;
    }
}

// vim: syntax=php sw=4 ts=4 et:
