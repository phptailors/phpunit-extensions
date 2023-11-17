<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\AssertNotHasMethod;

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
function consume(string $method, $subject, string $message): void
{
    Assert::assertNotHasMethod($method, $subject, $message);
}

// vim: syntax=php sw=4 ts=4 et:
