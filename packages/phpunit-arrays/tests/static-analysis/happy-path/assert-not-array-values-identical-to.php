<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\AssertNotArrayValuesIdenticalTo;

use PHPUnit\Framework\ExpectationFailedException;
use Tailors\PHPUnit\ArrayValuesIdenticalToTrait;
use Tailors\PHPUnit\InvalidArgumentException;

class Assert extends \PHPUnit\Framework\Assert
{
    use ArrayValuesIdenticalToTrait;
}

/**
 * @throws ExpectationFailedException
 * @throws InvalidArgumentException
 */
function consume(array $expected, string $class): string
{
    Assert::assertNotArrayValuesIdenticalTo($expected, $class);

    return $class;
}

// vim: syntax=php sw=4 ts=4 et:
