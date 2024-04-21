<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\Operator;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\Common\Exporter;
use Tailors\PHPUnit\Common\ShortFailureDescriptionTrait;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\ComparatorWrapperInterface;

/**
 * Abstract base for constraints that examine values.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
abstract class AbstractConstraint extends Constraint implements ComparatorWrapperInterface, SelectionWrapperInterface
{
    use ShortFailureDescriptionTrait;

    /**
     * @var SelectionInterface
     */
    private $expected;

    /**
     * @var RecursiveUnwrapperInterface
     */
    private $unwrapper;

    /**
     * @var ComparatorInterface
     */
    private $comparator;

    final protected function __construct(
        ComparatorInterface $comparator,
        SelectionInterface $expected,
        RecursiveUnwrapperInterface $unwrapper
    ) {
        $this->comparator = $comparator;
        $this->expected = $expected;
        $this->unwrapper = $unwrapper;
    }

    /**
     * Returns an instance of SelectionInterface which defines expected values.
     */
    final public function getSelection(): SelectionInterface
    {
        return $this->expected;
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
            'is %s with %s %s specified',
            $this->expected->getSelector()->subject(),
            $this->expected->getSelector()->selectable(),
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
     * @throws ExpectationFailedException
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

            if ($this->expected->getSelector()->supports($other)) {
                $actual = $this->select($other);
                $f = new ComparisonFailure(
                    $this->expected,
                    $other,
                    Exporter::export($this->expected, true),
                    Exporter::export($actual, true)
                );
            }

            $this->fail($other, $description, $f);
        }

        return null;
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
                'fails to be %s with %s %s specified',
                $this->expected->getSelector()->subject(),
                $this->expected->getSelector()->selectable(),
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
        if (!$this->expected->getSelector()->supports($other)) {
            return false;
        }
        $actual = $this->unwrapper->unwrap($this->select($other));
        $expect = $this->unwrapper->unwrap($this->expected);

        return $this->comparator->compare($expect, $actual);
    }

    /**
     * @param mixed $subject
     */
    private function select($subject): ValuesInterface
    {
        return (new RecursiveSelector($this->expected))->select($subject);
    }
}

// vim: syntax=php sw=4 ts=4 et:
