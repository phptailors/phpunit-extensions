<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Tailors\PHPUnit;

/**
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class InvalidArgumentException extends \InvalidArgumentException implements ExceptionInterface
{
    public static function fromBackTrace(int $argument, string $expected, string $provided, int $distance = 1): self
    {
        $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1 + $distance);

        /**
         * @psalm-var string|null $caller['class']
         * @psalm-var callable-string $caller['function']
         */
        $caller = $stack[$distance];

        if (null !== ($class = $caller['class'] ?? null)) {
            $scope = $class.'::';
        } else {
            $scope = '';
        }
        $function = sprintf('%s%s', $scope, $caller['function']);

        return self::fromFunction($function, $argument, $expected, $provided);
    }

    public static function fromFunction(string $function, int $argument, string $expected, string $provided): self
    {
        return new self(
            sprintf(
                'Argument #%d of %s() must be %s, %s given.',
                $argument,
                $function,
                $expected,
                $provided
            )
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
