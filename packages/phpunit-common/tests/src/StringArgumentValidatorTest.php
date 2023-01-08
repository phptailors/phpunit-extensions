<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\StringArgumentValidator
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class StringArgumentValidatorTest extends TestCase
{
    public function testValidateSucceeds(): void
    {
        $validator = new StringArgumentValidator('is_numeric', 'a numeric string');
        self::assertNull($validator->validate(1, '123'));
    }

    public function testValidateThrowsInvalidArgumentException(): void
    {
        $message = sprintf('Argument 1 passed to %s() must be a numeric string, \'foo\' given.', __METHOD__);

        $validator = new StringArgumentValidator('is_numeric', 'a numeric string');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        $validator->validate(1, 'foo');
    }
}

// vim: syntax=php sw=4 ts=4 et:
