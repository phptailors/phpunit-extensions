<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\AssertClassPropertiesEqualTo;

use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Tailors\PHPUnit\ClassPropertiesEqualToTrait;

class Assert extends \PHPUnit\Framework\Assert
{
    use ClassPropertiesEqualToTrait;
}

/**
 * @throws ExpectationFailedException
 * @throws \Tailors\PHPUnit\InvalidArgumentException
 */
function consume(array $expected, string $class): string
{
    Assert::assertClassPropertiesEqualTo($expected, $class);

    return $class;
}

// vim: syntax=php sw=4 ts=4 et:
