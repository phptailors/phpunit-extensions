<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\AssertHasMethod;

use PHPUnit\Framework\ExpectationFailedException;
use Tailors\PHPUnit\HasMethodTrait;
use Tailors\PHPUnit\InvalidArgumentException;

class Assert extends \PHPUnit\Framework\Assert
{
    use HasMethodTrait;
}

/**
 * @param mixed $subject
 *
 * @throws ExpectationFailedException
 * @throws InvalidArgumentException
 */
function consume(string $method, $subject): void
{
    Assert::assertHasMethod($method, $subject);
}

// vim: syntax=php sw=4 ts=4 et:
