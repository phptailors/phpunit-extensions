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
final class StringArgumentValidator
{
    /**
     * @var callable
     */
    private $validator;

    /**
     * @var string
     */
    private $expected;

    public function __construct(callable $validator, string $expected)
    {
        $this->validator = $validator;
        $this->expected = $expected;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validate(int $argument, string $value, int $distance = 1): void
    {
        if (!call_user_func($this->validator, $value)) {
            $this->throwInvalidArgumentException($argument, $value, 1 + $distance);
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function throwInvalidArgumentException(int $argument, string $value, int $distance = 1): void
    {
        $provided = sprintf("'%s'", addslashes($value));

        throw InvalidArgumentException::fromBackTrace($argument, $this->expected, $provided, 1 + $distance);
    }
}

// vim: syntax=php sw=4 ts=4 et:
