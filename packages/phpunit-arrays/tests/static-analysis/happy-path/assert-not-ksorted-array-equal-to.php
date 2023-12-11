<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\AssertNotKsortedArrayEqualTo;

class Assert extends \PHPUnit\Framework\Assert
{
    use \Tailors\PHPUnit\KsortedArrayEqualToTrait;
}

/**
 * @throws \PHPUnit\Framework\ExpectationFailedException
 * @throws \Tailors\PHPUnit\InvalidArgumentException
 */
function consume(array $expected, array $actual): array
{
    Assert::assertNotKsortedArrayEqualTo($expected, $actual);

    return $actual;
}

// vim: syntax=php sw=4 ts=4 et:
