<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Constraint;

use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Values\ConstraintTestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
abstract class PropertiesConstraintTestCase extends ConstraintTestCase
{
    // @codeCoverageIgnoreStart

    public static function provArrayWithNonStringKeys(): array
    {
        return [
            'PropertiesConstraintTestTrait.php:'.__LINE__ => [
                'array' => [
                    'a' => 'A',
                    0   => 'B',
                ],
                'count' => 1,
            ],
            'PropertiesConstraintTestTrait.php:'.__LINE__ => [
                'array' => [
                    'a' => 'A',
                    0   => 'B',
                    2   => 'C',
                    7   => 'D',
                    'e' => 'E',
                ],
                'count' => 3,
            ],
        ];
    }

    // @codeCoverageIgnoreEnd

    /**
     * @dataProvider provArrayWithNonStringKeys
     */
    final public function testCreateWithNonStringKeys(array $array, int $count): void
    {
        $this->examineCreateWithNonStringKeys($array, $count);

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * Assert that constraint constructor throws InvalidArgumentException with
     * appropriate message when provided with an array having one or more
     * non-string keys.
     *
     * @param array $array An array with non-string keys to be passed as an argument to $function
     * @param int   $count Number of non-string keys in $array
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    private function examineCreateWithNonStringKeys(array $array, int $count): void
    {
        $message = sprintf(
            'Argument 1 passed to %s::create() must be an associative array with string keys, '.
            'an array with %d non-string %s given',
            get_class($this->createConstraint([])),
            $count,
            $count > 1 ? 'keys' : 'key'
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        $this->createConstraint($array);

        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd
}

// vim: syntax=php sw=4 ts=4 et:
