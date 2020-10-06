<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\AssertNotClassPropertiesEqualTo;

class Assert extends \PHPUnit\Framework\Assert
{
    use \Tailors\PHPUnit\ClassPropertiesEqualToTrait;
}

/**
 * @throws \PHPUnit\Framework\ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 * @throws \Tailors\PHPUnit\InvalidArgumentException
 */
function consume(array $expected, string $class): string
{
    Assert::assertNotClassPropertiesEqualTo($expected, $class);

    return $class;
}

// vim: syntax=php sw=4 ts=4 et:
