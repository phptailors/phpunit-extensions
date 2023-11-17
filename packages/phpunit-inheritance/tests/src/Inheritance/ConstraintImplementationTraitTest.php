<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Inheritance;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\InvalidReturnValueException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class FaultyConstraint1 extends AbstractConstraint
{
    use ConstraintImplementationTrait {
        inheritance as public;
    }

    /**
     * @var string
     */
    private static $verb = 'is string';

    /**
     * @var string
     */
    private static $negatedVerb = 'is not a string';

    /**
     * @var array
     *
     * @psalm-var array{0:callable, 1:string}
     */
    private static $validation = ['is_string', 'a string'];

    /**
     * @var callable
     *
     * @psalm-var callable
     */
    private static $inheritance = 'strlen';

    /**
     * @var array
     *
     * @psalm-var array{0:callable}
     */
    private static $supports = ['is_string'];
}

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class FaultyConstraint2 extends AbstractConstraint
{
    use ConstraintImplementationTrait {
        inheritance as public;
    }

    /**
     * @var string
     */
    private static $verb = 'is string';

    /**
     * @var string
     */
    private static $negatedVerb = 'is not a string';

    /**
     * @var array
     *
     * @psalm-var array{0:callable, 1:string}
     */
    private static $validation = ['is_string', 'a string'];

    /**
     * @var callable
     *
     * @psalm-var callable
     */
    private static $inheritance = [self::class, 'arrayWithStrlen'];

    /**
     * @var array
     *
     * @psalm-var array{0:callable}
     */
    private static $supports = ['is_string'];

    public static function arrayWithStrlen(string $str): array
    {
        return [strlen($str)];
    }
}

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Inheritance\ConstraintImplementationTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ConstraintImplementationTraitTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testInheritanceThrowsInvalidReturnValueException(): void
    {
        $constraint = FaultyConstraint1::create('stdClass');

        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage(sprintf(
            'Return value of %s::%s() must be of the type array, %s returned',
            FaultyConstraint1::class,
            '$inheritance',
            gettype(0)
        ));

        $constraint->inheritance('');
    }

    /**
     * @runInSeparateProcess
     */
    public function testInheritanceThrowsInvalidReturnValueException2(): void
    {
        $constraint = FaultyConstraint2::create('stdClass');

        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage(sprintf(
            'Return value of %s::%s() must be of the type array of strings, %s returned',
            FaultyConstraint2::class,
            '$inheritance',
            gettype([])
        ));

        $constraint->inheritance('');
    }
}
