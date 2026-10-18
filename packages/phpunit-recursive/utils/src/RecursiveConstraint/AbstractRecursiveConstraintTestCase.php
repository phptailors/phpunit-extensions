<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use PHPUnit\Framework\Constraint\Constraint;
use Tailors\PHPUnit\Common\AbstractConstraintTestCase;
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type CtorArgs = list{ArrayLike}
 *
 * @psalm-template ConstraintClass of Constraint
 *
 * @template-extends AbstractConstraintTestCase<ConstraintClass, list{ArrayLike}>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
abstract class AbstractRecursiveConstraintTestCase extends AbstractConstraintTestCase
{
    abstract public static function complement(): string;

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      actual: mixed,
     *      message: string
     * }>
     *
     * @codeCoverageIgnore
     */
    final public static function provConstraintUnaryOperatorFailure(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [[]],
            'actual'   => null,
            'message'  => self::message('null'),
        ];
    }

    /**
     * Assembles expected failure message out of pieces.
     *
     * @param string $exported A noun representing the actual value, such as
     *                         "123" or "array" or "object stdClass"
     * @param bool   $negative indicates whether the generated message is for the
     *                         tested constraint (false) or a constraint negated
     *                         with LogicalNot (true)
     *
     * @codeCoverageIgnore Called from providers only.
     */
    final protected static function message(string $exported, bool $negative = false): string
    {
        return sprintf('Failed asserting that %s.', self::predicate($exported, $negative));
    }

    /**
     * Assembles a predicate which is a part of failure message.
     *
     * @param string $exported A noun representing the actual value, such as
     *                         "123" or "array" or "object stdClass"
     * @param bool   $negative indicates whether the generated predicate is for
     *                         the constraint under test (false) or a constraint
     *                         negated with LogicalNot (true)
     *
     * @codeCoverageIgnore Called from providers only.
     */
    final protected static function predicate(string $exported, bool $negative = false): string
    {
        return sprintf('%s %s %s', $exported, $negative ? 'fails to be' : 'is', static::complement());
    }
}

// vim: syntax=php sw=4 ts=4 et:
