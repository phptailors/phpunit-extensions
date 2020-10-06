<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use Tailors\PHPUnit\Constraint\HasPregCaptures;

trait HasPregCapturesTrait
{
    /**
     * Evaluates a \PHPUnit\Framework\Constraint matcher object.
     *
     * @param mixed $value
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    abstract public static function assertThat($value, Constraint $constraint, string $message = ''): void;

    /**
     * Asserts that an array of *$matches* returned from ``preg_match()`` has
     * capture groups as specified in *$expected*.
     *
     * Checks only entries present in *$expected*, so *$expected = []* accepts
     * any array. Special values may be used in the expectations:
     *
     * - ``['foo' => false]`` asserts that group ``'foo'`` was not captured,
     * - ``['foo' => true]`` asserts that group ``'foo'`` was captured,
     * - ``['foo' => 'FOO']`` asserts that group ``'foo'`` was captured and it's value equals ``'FOO'``.
     *
     * Boolean expectations (``['foo' => true]`` or ``['foo' => false]``) work
     * properly only with arrays obtained from ``preg_match()`` invoked with
     * ``PREG_UNMATCHED_AS_NULL`` flag.
     *
     * @param array  $expected
     *                         An array of expectations
     * @param array  $matches
     *                         An array of preg matches to be examined
     * @param string $message
     *                         Additional message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertHasPregCaptures(array $expected, array $matches, string $message = ''): void
    {
        self::assertThat($matches, self::hasPregCaptures($expected), $message);
    }

    /**
     * Negated assertHasPregCaptures().
     *
     * @param array  $expected
     *                         An array of expectations
     * @param array  $matches
     *                         An array of preg matches to be examined
     * @param string $message
     *                         Additional message
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function assertNotHasPregCaptures(array $expected, array $matches, string $message = ''): void
    {
        self::assertThat($matches, new LogicalNot(self::hasPregCaptures($expected)), $message);
    }

    /**
     * Accepts arrays of matches returned from ``preg_match()`` having capture
     * groups as specified in *$excpected*.
     *
     * Checks only entries present in *$expected*, so *$expected = []* accepts
     * any array. Special values may be used in the expectations:
     *
     * - ``['foo' => false]`` asserts that group ``'foo'`` was not captured,
     * - ``['foo' => true]`` asserts that group ``'foo'`` was captured,
     * - ``['foo' => 'FOO']`` asserts that group ``'foo'`` was captured and its value equals ``'FOO'``.
     *
     * Boolean expectations (``['foo' => true]`` or ``['foo' => false]``) work
     * properly only with arrays obtained from ``preg_match()`` invoked with
     * ``PREG_UNMATCHED_AS_NULL`` flag.
     *
     * @throws \Tailors\PHPUnit\InvalidArgumentException
     */
    public static function hasPregCaptures(array $expected): HasPregCaptures
    {
        return HasPregCaptures::create($expected);
    }
}

// vim: syntax=php sw=4 ts=4 et:
