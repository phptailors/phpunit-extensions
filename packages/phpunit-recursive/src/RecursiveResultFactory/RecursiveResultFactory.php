<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversal;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversalInterface;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class RecursiveResultFactory implements RecursiveResultFactoryInterface
{
    public function __construct(
        private readonly RecursiveExpectedResultFactoryVisitorInterface $expectedResultVisitor,
        private readonly RecursiveActualResultFactoryVisitorInterface $actualResultVisitor,
        private readonly RecursiveTraversalInterface $traversal
    ) {}

    public static function create(): self
    {
        $expectedResultVisitor = new RecursiveExpectedResultFactoryVisitor();
        $actualResultVisitor = new RecursiveActualResultFactoryVisitor();
        $traversal = new RecursiveTraversal();

        return new self($expectedResultVisitor, $actualResultVisitor, $traversal);
    }

    /**
     * @psalm-param ArrayLike $array
     *
     * @psalm-template TValueSelectorSupportedInput
     * @psalm-template TResultFactorySupportedInput
     */
    public function supports(iterable $array, mixed $input): bool
    {
        if ($array instanceof ValueSelectorWrapperInterface) {
            /** @psalm-var ValueSelectorWrapperInterface<TValueSelectorSupportedInput> $array */
            $valueSelector = $array->getValueSelector();
            if (!$valueSelector->supports($input)) {
                return false;
            }

            if (!$array instanceof ResultFactoryWrapperInterface) {
                return false;
            }

            /** @psalm-var ResultFactoryWrapperInterface<TResultFactorySupportedInput> $array */
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
     * @psalm-param ArrayLike $array
     */
    public function getActualResult(iterable $array, mixed $input): mixed
    {
        $this->actualResultVisitor->begin($input);

        $this->traversal->walk($array, $this->actualResultVisitor);

        $this->actualResultVisitor->end();

        return $this->actualResultVisitor->result();
    }

    /**
     * @psalm-param ArrayLike $array
     */
    public function getExpectedResult(iterable $array): mixed
    {
        $this->expectedResultVisitor->begin();

        $this->traversal->walk($array, $this->expectedResultVisitor);

        $this->expectedResultVisitor->end();

        return $this->expectedResultVisitor->result();
    }
}

// vim: syntax=php sw=4 ts=4 et:
