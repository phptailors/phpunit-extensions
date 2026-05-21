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
use Tailors\PHPUnit\ArraySpec\ArraySpecInterface;
use Tailors\PHPUnit\Common\Exporter;
use Tailors\PHPUnit\Common\ShortFailureDescriptionTrait;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\InternalErrorException;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactoryInterface;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapperInterface;
use Tailors\PHPUnit\Selector\ValueSelectorInterface;
use Tailors\PHPUnit\Values\ValuesInterface;
use Tailors\PHPUnit\Values\ValuesWrapperInterface;

/**
 * Abstract base for constraints that examine values.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
abstract class AbstractRecursiveConstraint extends Constraint
{
    use ShortFailureDescriptionTrait;

    /**
     * @var ArraySpecInterface
     */
    private $arraySpec;

    /**
     * @var ComparatorInterface
     */
    private $comparator;

    /**
     * @var RecursiveResultFactoryInterface
     */
    private $expectedResultFactory;

    /**
     * @var RecursiveResultFactoryInterface
     */
    private $actualResultFactory;

    /**
     * @var RecursiveResultUnwrapperInterface
     */
    private $expectedResultUnwrapper;

    /**
     * @var RecursiveResultUnwrapperInterface
     */
    private $actualResultUnwrapper;

    final protected function __construct(
        ArraySpecInterface $arraySpec,
        ComparatorInterface $comparator,
        RecursiveResultFactoryInterface $expectedResultFactory,
        RecursiveResultFactoryInterface $actualResultFactory,
        RecursiveResultUnwrapperInterface $expectedResultUnwrapper,
        RecursiveResultUnwrapperInterface $actualResultUnwrapper,
    ) {
        $this->arraySpec = $arraySpec;
        $this->comparator = $comparator;
        $this->expectedResultFactory = $expectedResultFactory;
        $this->actualResultFactory = $actualResultFactory;
        $this->expectedResultUnwrapper = $expectedResultUnwrapper;
        $this->actualResultUnwrapper = $actualResultUnwrapper;
    }

    /**
     * Returns a string representation of the constraint.
     */
    final public function toString(): string
    {
        return sprintf(
            'is %s with %s %s specified',
            $this->valueSelector->subject(),
            $this->valueSelector->selectable(),
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

            if ($this->valueSelector->supports($other)) {
                $actual = $this->select($other);
                $f = new ComparisonFailure(
                    $this->arraySpec,
                    $other,
                    Exporter::export($this->arraySpec, true),
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
                $this->valueSelector->subject(),
                $this->valueSelector->selectable(),
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
        if (!$this->valueSelector->supports($other)) {
            return false;
        }
        $actual = $this->actualResultUnwrapper->unwrap($this->actualResultFactory->getResult($other));
        $expect = $this->expectedResultUnwrapper->unwrap($this->expectedResultFactory->getResult($this->arraySpec));

        return $this->comparator->compare($expect, $actual);
    }
//
//    /**
//     * @param mixed $subject
//     */
//    private function select($subject): ValuesInterface
//    {
//        $visitor = new RecursiveSelectorVisitor($this->valueSelector, $subject);
//
//        (new RecursiveTraversal())->walk($this->arraySpec, $visitor);
//
//        $result = $visitor->result();
//
//        if (!$result instanceof ValuesInterface) {
//            // @codeCoverageIgnoreStart
//            $type = is_object($result) ? get_class($result) : gettype($result);
//
//            /** @psalm-suppress MissingThrowsDocblock */
//            throw InternalErrorException::fromBackTrace("recursive walk resulted with {$type}", 0);
//            // @codeCoverageIgnoreEnd
//        }
//
//        return $result;
//    }
}

// vim: syntax=php sw=4 ts=4 et:
