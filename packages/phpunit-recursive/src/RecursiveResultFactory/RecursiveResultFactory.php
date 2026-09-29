<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversal;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversalInterface;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
final class RecursiveResultFactory implements RecursiveResultFactoryInterface
{
    /**
     * @var RecursiveExpectedResultFactoryVisitorInterface
     */
    private $expectedResultVisitor;

    /**
     * @var RecursiveActualResultFactoryVisitorInterface
     *
     * @psalm-readonly
     */
    private $actualResultVisitor;

    /**
     * @var RecursiveTraversalInterface
     *
     * @psalm-readonly
     */
    private $traversal;

    public function __construct(
        RecursiveExpectedResultFactoryVisitorInterface $expectedResultVisitor,
        RecursiveActualResultFactoryVisitorInterface $actualResultVisitor,
        RecursiveTraversalInterface $traversal
    ) {
        $this->expectedResultVisitor = $expectedResultVisitor;
        $this->actualResultVisitor = $actualResultVisitor;
        $this->traversal = $traversal;
    }

    public static function create(): self
    {
        $expectedResultVisitor = new RecursiveExpectedResultFactoryVisitor();
        $actualResultVisitor = new RecursiveActualResultFactoryVisitor();
        $traversal = new RecursiveTraversal();

        return new self($expectedResultVisitor, $actualResultVisitor, $traversal);
    }

    /**
     * @param mixed $input
     *
     * @psalm-param ArrayLike $array
     */
    public function supports(iterable $array, $input): bool
    {
        if ($array instanceof ValueSelectorWrapperInterface) {
            $valueSelector = $array->getValueSelector();
            if (!$valueSelector->supports($input)) {
                return false;
            }

            if (!$array instanceof ResultFactoryWrapperInterface) {
                return false;
            }

            $resultFactory = $array->getResultFactory();

            return $resultFactory->supports([]);
        }

        if ($array instanceof ResultFactoryWrapperInterface) {
            $resultFactory = $array->getResultFactory();

            return $resultFactory->supports($input);
        }

        return is_array($array) && is_array($input);
    }

    /**
     * @param mixed $input
     *
     * @return mixed
     *
     * @psalm-param ArrayLike $array
     */
    public function getActualResult(iterable $array, $input)
    {
        $this->actualResultVisitor->begin($input);

        $this->traversal->walk($array, $this->actualResultVisitor);

        $this->actualResultVisitor->end();

        return $this->actualResultVisitor->result();
    }

    /**
     * @return mixed
     *
     * @psalm-param ArrayLike $array
     */
    public function getExpectedResult(iterable $array)
    {
        $this->expectedResultVisitor->begin();

        $this->traversal->walk($array, $this->expectedResultVisitor);

        $this->expectedResultVisitor->end();

        return $this->expectedResultVisitor->result();
    }
}

// vim: syntax=php sw=4 ts=4 et:
