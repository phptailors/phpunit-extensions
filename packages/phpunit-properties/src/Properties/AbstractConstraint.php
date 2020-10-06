<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\Operator;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Exporter\Exporter as SebastianBergmannExporter;

/**
 * Abstract base for constraints that examine subject's properties.
 *
 * Objects and classes are examples of subjects that may be examined.
 * Support for other kinds of beings may be implemented if necessary.
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
abstract class AbstractConstraint extends Constraint implements ExpectedPropertiesInterface
{
    use ExpectedPropertiesDecoratorTrait;

    /**
     * @var ExpectedPropertiesInterface
     */
    private $expected;

    /**
     * @var RecursivePropertiesUnwrapperInterface
     */
    private $unwrapper;

    /**
     * @var null|Exporter
     */
    private $exporter;

    /**
     * @var ComparatorInterface
     */
    private $comparator;

    final protected function __construct(
        ComparatorInterface $comparator,
        ExpectedPropertiesInterface $expected,
        RecursivePropertiesUnwrapperInterface $unwrapper
    ) {
        $this->comparator = $comparator;
        $this->expected = $expected;
        $this->unwrapper = $unwrapper;
    }

    /**
     * Returns an instance of ExpectedPropertiesInterface which specifies
     * expectations that the constrant uses to examine subjects' properties.
     */
    final public function getExpectedProperties(): ExpectedPropertiesInterface
    {
        return $this->expected;
    }

    /**
     * Returns a string representation of the constraint.
     */
    final public function toString(): string
    {
        return sprintf(
            'is %s with properties %s specified',
            $this->expected->getPropertySelector()->subject(),
            $this->comparator->adjective()
        );
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
    final public function toStringInContext(Operator $operator, $role): string
    {
        if ($operator instanceof LogicalNot) {
            return sprintf(
                'fails to be %s with properties %s specified',
                $this->expected->getPropertySelector()->subject(),
                $this->comparator->adjective()
            );
        }

        return '';
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
     * @param mixed $other
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

            if ($this->getPropertySelector()->canSelectFrom($other)) {
                $actual = $this->selectActualProperties($other);
                $f = new ComparisonFailure(
                    $this->expected,
                    $other,
                    $this->exporter()->export($this->expected),
                    $this->exporter()->export($actual)
                );
            }

            $this->fail($other, $description, $f);
        }

        return null;
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param mixed $other value or object to evaluate
     */
    final public function matches($other): bool
    {
        if (!$this->getPropertySelector()->canSelectFrom($other)) {
            return false;
        }
        $actual = $this->unwrapper->unwrap($this->selectActualProperties($other));
        $expect = $this->unwrapper->unwrap($this->expected);

        return $this->comparator->compare($expect, $actual);
    }

    final protected function exporter(): SebastianBergmannExporter
    {
        if (null === $this->exporter) {
            $this->exporter = new Exporter();
        }

        return $this->exporter;
    }

    /**
     * Returns the description of the failure.
     *
     * The beginning of failure messages is "Failed asserting that" in most
     * cases. This method should return the second part of that sentence.
     *
     * @param mixed $other evaluated value or object
     */
    final protected function failureDescription($other): string
    {
        return $this->short($other).' '.$this->toString();
    }

    /**
     * Returns the description of the failure when this constraint appears in
     * context of an $operator expression.
     *
     * The purpose of this method is to provide meaningful failue description
     * in context of operators such as LogicalNot. Native PHPUnit constraints
     * are supported out of the box by LogicalNot, but externally developed
     * ones had no way to provide correct messages in this context.
     *
     * The method shall return empty string, when it does not handle
     * customization by itself.
     *
     * @param Operator $operator the $operator of the expression
     * @param mixed    $role     role of $this constraint in the $operator expression
     * @param mixed    $other    evaluated value or object
     */
    final protected function failureDescriptionInContext(Operator $operator, $role, $other): string
    {
        $string = $this->toStringInContext($operator, $role);

        if ('' === $string) {
            return '';
        }

        return $this->short($other).' '.$string;
    }

    /**
     * @param mixed $subject
     */
    private function selectActualProperties($subject): ActualPropertiesInterface
    {
        return (new RecursivePropertiesSelector($this->expected))->selectProperties($subject);
    }

    /**
     * Returns short representation of $subject for failureDescription().
     *
     * @param mixed $subject
     */
    private function short($subject): string
    {
        if (is_object($subject)) {
            return 'object '.get_class($subject);
        }

        if (is_array($subject)) {
            return 'array';
        }

        if (is_string($subject) && class_exists($subject)) {
            // avoid converting anonymous class names to binary strings.
            return $subject;
        }

        return $this->exporter()->export($subject);
    }
}

// vim: syntax=php sw=4 ts=4 et:
