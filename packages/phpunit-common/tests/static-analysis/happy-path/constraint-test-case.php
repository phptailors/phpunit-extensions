<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\ConstraintTestCase;

use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\MockObject\RuntimeException;
use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\Constraint\TestCase;
use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @throws Exception
 * @throws ExpectationFailedException
 * @throws RuntimeException
 * @throws CircularDependencyException
 * @throws InvalidArgumentException
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
