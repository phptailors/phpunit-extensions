<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\AssertNotObjectPropertiesEqualTo;

class Assert extends \PHPUnit\Framework\Assert
{
    use \Tailors\PHPUnit\ObjectPropertiesEqualToTrait;
}

/**
 * @throws \PHPUnit\Framework\ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 * @throws \Tailors\PHPUnit\InvalidArgumentException
 */
function consume(array $expected, object $object): object
{
    Assert::assertNotObjectPropertiesEqualTo($expected, $object);

    return $object;
}

// vim: syntax=php sw=4 ts=4 et:
