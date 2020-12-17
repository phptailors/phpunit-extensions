<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\Operator;
use SebastianBergmann\Comparator\ComparisonFailure;
use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\Common\ShortFailureDescriptionTrait;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\ComparatorWrapperInterface;

/**
 * Abstract base class for constraints that compare key-sorted arrays.
 */
abstract class AbstractKsortedConstraint extends Constraint implements ComparatorWrapperInterface
{
    use ShortFailureDescriptionTrait;

    /**
     * @var array
     */
    private $expected;

    /**
     * @var ComparatorInterface
     */
    private $comparator;

    /**
     * @var int
     */
    private $flags;

    final protected function __construct(ComparatorInterface $comparator, array $expected, int $flags)
    {
        $this->comparator = $comparator;
        $this->expected = $expected;
        $this->flags = $flags;
    }

    /**
     * Returns an instance of ComparatorInterface which implements comparison operator.
     */
    final public function getComparator(): ComparatorInterface
    {
        return $this->comparator;
    }

    /**
     * Returns a string representation of the constraint.
     */
    final public function toString(): string
    {
        return sprintf(
            'is an array %s specified one when ksorted',
            $this->comparator->adjective()
        );
    }

    /**
     * Evaluates the constraint for parameter $other.
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. null is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @param mixed  $other
     * @param string $description
     * @param bool   $returnResult
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws CircularDependencyException
     */
    final public function evaluate($other, string $description = '', bool $returnResult = false): ?bool
    {
        $success = $this->matches($other);

        if ($returnResult) {
            return $success;
        }

        if (!$success) {
            $f = null;

            if ($this->supports($other)) {
                $f = new ComparisonFailure(
                    $this->expected,
                    $other,
                    $this->exporter()->export($this->ksorted($this->expected)),
                    $this->exporter()->export($this->ksorted($other))
                );
            }

            $this->fail($other, $description, $f);
        }

        return null;
    }

    /**
     * Returns key-sorted copy of $array.
     */
    final public function ksorted(array $array): array
    {
        ksort($array, $this->flags);

        return $array;
    }

    /**
     * Returns a custom string representation of the constraint object when it
     * appears in context of an $operator expression.
     *
     * The purpose of this method is to provide meaningful descriptive string
     * in context of operators such as LogicalNot. Native PHPUnit constraints
     * are supported out of the box by LogicalNot, but externally developed
     * ones had no way to provide correct strings in this context.
     *
     * The method shall return empty string, when it does not handle
     * customization by itself.
     *
     * @param Operator $operator the $operator of the expression
     * @param mixed    $role     role of $this constraint in the $operator expression
     */
    final protected function toStringInContext(Operator $operator, $role): string
    {
        if ($operator instanceof LogicalNot) {
            return sprintf(
                'fails to be an array %s specified one when ksorted',
                $this->comparator->adjective()
            );
        }

        return '';
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     */
    final protected function matches($other): bool
    {
        if (!$this->supports($other)) {
            return false;
        }

        return $this->comparator->compare($this->ksorted($this->expected), $this->ksorted($other));
    }

    /**
     * @param mixed $other value or object to evaluate
     *
     * @psalm-assert-if-true array $other
     */
    final protected function supports($other): bool
    {
        return is_array($other);
    }
}

// vim: syntax=php sw=4 ts=4 et:
