<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\AssertNotObjectPropertiesIdenticalTo;

use PHPUnit\Framework\ExpectationFailedException;
use Tailors\PHPUnit\ObjectPropertiesIdenticalToTrait;

class Assert extends \PHPUnit\Framework\Assert
{
    use ObjectPropertiesIdenticalToTrait;
}

/**
 * @throws ExpectationFailedException
 * @throws \Tailors\PHPUnit\InvalidArgumentException
 */
function consume(array $expected, object $object): object
{
    Assert::assertNotObjectPropertiesIdenticalTo($expected, $object);

    return $object;
}

// vim: syntax=php sw=4 ts=4 et:
