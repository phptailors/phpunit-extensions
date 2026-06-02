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
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\Operator;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
use Tailors\PHPUnit\Common\Exporter;
use Tailors\PHPUnit\Common\ShortFailureDescriptionTrait;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactoryInterface;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapperInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
abstract class AbstractRecursiveConstraint extends Constraint
{
    use ShortFailureDescriptionTrait;

    /**
     * @var iterable
     *
     * @psalm-var ArrayLike
     *
     * @psalm-readonly
     */
    private $expected;

    /**
     * @var ComparatorInterface
     */
    private $comparator;

    /**
     * @var RecursiveResultFactoryInterface
     */
    private $recursiveResultFactory;

    /**
     * @var RecursiveResultUnwrapperInterface
     */
    private $recursiveResultUnwrapper;

    /**
     * @param mixed $expected
     *
     * @psalm-param ArrayLike $expected
     */
    final protected function __construct(
        iterable $expected,
        ComparatorInterface $comparator,
        RecursiveResultFactoryInterface $recursiveResultFactory,
        RecursiveResultUnwrapperInterface $recursiveResultUnwrapper
    ) {
        $this->expected = $expected;
        $this->comparator = $comparator;
        $this->recursiveResultFactory = $recursiveResultFactory;
        $this->recursiveResultUnwrapper = $recursiveResultUnwrapper;
    }

    /**
     * Returns a string representation of the constraint.
     */
    final public function toString(): string
    {
        if ($this->expected instanceof ValueSelectorWrapperInterface) {
            $valueSelector = $this->expected->getValueSelector();

            return sprintf(
                'is %s with %s %s specified',
                $valueSelector->subject(),
                $valueSelector->selectable(),
                $this->comparator->adjective()
            );
        }

        return 'satisfies the recursive constraint';
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
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     */
    final public function evaluate($other, string $description = '', bool $returnResult = false): ?bool
    {
        $success = $this->matches($other);

        if ($returnResult) {
            return $success;
        }

        if (!$success) {
            $f = null;

            if ($this->recursiveResultFactory->supports($this->expected, $other)) {
                $expectResult = $this->recursiveResultFactory->getResult(false, $this->expected, $this->expected);
                $actualResult = $this->recursiveResultFactory->getResult(true, $this->expected, $other);
                $f = new ComparisonFailure(
                    $this->expected,
                    $other,
                    Exporter::export($expectResult, true),
                    Exporter::export($actualResult, true)
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
            if ($this->expected instanceof  ValueSelectorWrapperInterface) {
                $valueSelector = $this->expected->getValueSelector();

                return sprintf(
                    'fails to be %s with %s %s specified',
                    $valueSelector->subject(),
                    $valueSelector->selectable(),
                    $this->comparator->adjective()
                );
            }

            return 'fails to satisfy the recursive constraint';
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
        if (!$this->recursiveResultFactory->supports($this->expected, $other)) {
            return false;
        }

        $expectResult = $this->recursiveResultFactory->getResult(false, $this->expected, $this->expected);
        $actualResult = $this->recursiveResultFactory->getResult(true, $this->expected, $other);

        $expectArray = $this->recursiveResultUnwrapper->unwrap(false, $expectResult);
        $actualArray = $this->recursiveResultUnwrapper->unwrap(true, $actualResult);

        return $this->comparator->compare($expectArray, $actualArray);
    }
}

// vim: syntax=php sw=4 ts=4 et:
