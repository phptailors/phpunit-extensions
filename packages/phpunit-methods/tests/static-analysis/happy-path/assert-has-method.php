<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\AssertHasMethod;

class Assert extends \PHPUnit\Framework\Assert
{
    use \Tailors\PHPUnit\HasMethodTrait;
}

/**
 * @param mixed $subject
 *
 * @throws \PHPUnit\Framework\ExpectationFailedException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 * @throws \Tailors\PHPUnit\InvalidArgumentException
 */
function consume(string $method, $subject): void
{
    Assert::assertHasMethod($method, $subject);
}

// vim: syntax=php sw=4 ts=4 et:
