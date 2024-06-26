<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\AssertImplementsInterface;

use PHPUnit\Framework\ExpectationFailedException;
use Tailors\PHPUnit\ImplementsInterfaceTrait;
use Tailors\PHPUnit\InvalidArgumentException;

class Assert extends \PHPUnit\Framework\Assert
{
    use ImplementsInterfaceTrait;
}

/**
 * @throws ExpectationFailedException
 * @throws InvalidArgumentException
 */
function consume(string $expected, string $actual): string
{
    Assert::assertImplementsInterface($expected, $actual);

    return $actual;
}

// vim: syntax=php sw=4 ts=4 et:
