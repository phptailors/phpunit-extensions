<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\ConstraintTestCase;

use Tailors\PHPUnit\Constraint\TestCase;

/**
 * @throws \PHPUnit\Framework\Exception
 * @throws \PHPUnit\Framework\ExpectationFailedException
 * @throws \PHPUnit\Framework\MockObject\ReflectionException
 * @throws \PHPUnit\Framework\MockObject\RuntimeException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 * @throws \Tailors\PHPUnit\CircularDependencyException
 * @throws \Tailors\PHPUnit\InvalidArgumentException
 */
function consume(TestCase $test): TestCase
{
    $test->examineCreateConstraint([]);
    $test->examineConstraintUnaryOperatorFailure([], null, '');

    $test->examineConstraintMatchSucceeds([], []);
    $test->examineConstraintMatchFails([], [], '');
    $test->examineNotConstraintMatchSucceeds([], []);
    $test->examineNotConstraintMatchFails([], [], '');

    return $test;
}

// vim: syntax=php sw=4 ts=4 et:
