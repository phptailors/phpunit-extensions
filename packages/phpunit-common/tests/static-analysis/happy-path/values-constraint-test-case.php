<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Values\ConstraintTestCase;

/**
 * @throws \PHPUnit\Framework\Exception
 * @throws \PHPUnit\Framework\ExpectationFailedException
 * @throws \PHPUnit\Framework\MockObject\ReflectionException
 * @throws \PHPUnit\Framework\MockObject\RuntimeException
 * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
 * @throws \Tailors\PHPUnit\CircularDependencyException
 * @throws \Tailors\PHPUnit\InvalidArgumentException
 */
function consume(ConstraintTestCase $test): ConstraintTestCase
{
    $test->provCreate();
    $test->testCreate([], ['values' => TestCase::identicalTo([])]);

    $test->testFailureExceptionInUnaryOperatorContext();

    $test->examineValuesMatchSucceeds([], []);
    $test->examineValuesMatchFails([], [], '');
    $test->examineNotValuesMatchSucceeds([], []);
    $test->examineNotValuesMatchFails([], [], '');

    return $test;
}

// vim: syntax=php sw=4 ts=4 et:
