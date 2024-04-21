<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\AssertKsortedArrayEqualTo;

use PHPUnit\Framework\ExpectationFailedException;
use Tailors\PHPUnit\KsortedArrayEqualToTrait;

class Assert extends \PHPUnit\Framework\Assert
{
    use KsortedArrayEqualToTrait;
}

/**
 * @throws ExpectationFailedException
 * @throws \Tailors\PHPUnit\InvalidArgumentException
 */
function consume(array $expected, array $actual): array
{
    Assert::assertKsortedArrayEqualTo($expected, $actual);

    return $actual;
}

// vim: syntax=php sw=4 ts=4 et:
