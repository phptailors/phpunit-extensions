<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\AssertExtendsClass;

use PHPUnit\Framework\ExpectationFailedException;
use Tailors\PHPUnit\ExtendsClassTrait;
use Tailors\PHPUnit\InvalidArgumentException;

class Assert extends \PHPUnit\Framework\Assert
{
    use ExtendsClassTrait;
}

/**
 * @throws ExpectationFailedException
 * @throws InvalidArgumentException
 */
function consume(string $expected, string $actual): string
{
    Assert::assertExtendsClass($expected, $actual);

    return $actual;
}

// vim: syntax=php sw=4 ts=4 et:
