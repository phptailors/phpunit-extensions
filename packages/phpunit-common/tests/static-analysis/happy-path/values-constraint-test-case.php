<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\ValuesConstraintTestCase;

use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\ReflectionException;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\Values\ConstraintTestCase;

/**
 * @throws Exception
 * @throws ExpectationFailedException
 * @throws ReflectionException
 * @throws RuntimeException
 * @throws CircularDependencyException
 * @throws \Tailors\PHPUnit\InvalidArgumentException
 */
function consume(ConstraintTestCase $test): ConstraintTestCase
{
    $test->provCreateConstraint();
    $test->testCreateConstraint([], ['values' => TestCase::identicalTo([])]);

    $test->testConstraintUnaryOperatorFailure();

    $test->examineValuesMatchSucceeds([], []);
    $test->examineValuesMatchFails([], [], '');
    $test->examineNotValuesMatchSucceeds([], []);
    $test->examineNotValuesMatchFails([], [], '');

    return $test;
}

// vim: syntax=php sw=4 ts=4 et:
