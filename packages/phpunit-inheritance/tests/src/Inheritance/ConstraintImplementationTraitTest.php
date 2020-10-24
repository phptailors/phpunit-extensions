<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Inheritance;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\InvalidReturnValueException;

/**
 * @small
 * @covers \Tailors\PHPUnit\Inheritance\ConstraintImplementationTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class ConstraintImplementationTraitTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testInheritanceThrowsInvalidReturnValueException(): void
    {
        $constraint = FaultyConstraintImplementation::create('stdClass');

        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage(sprintf(
            'Return value of %s::%s() must be of the type array, %s returned',
            FaultyConstraintImplementation::class,
            '$inheritance',
            gettype(0)
        ));

        $constraint->inheritance('');
    }
}
