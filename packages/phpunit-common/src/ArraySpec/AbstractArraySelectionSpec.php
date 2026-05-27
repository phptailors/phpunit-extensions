<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArraySpec;

use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\Constraint\Operator;
use Tailors\PHPUnit\Expectation\ExpectationInterface;
use Tailors\PHPUnit\ResultFactory\ResultFactoryInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;


/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template SupportedInput
 * @psalm-template SupportedSubject
 *
 * @template-extends AbstractArraySpec<SupportedInput>
 * @template-implements ValueSelectorWrapperInterface<SupportedSubject>
 */
abstract class AbstractArraySelectionSpec extends AbstractArraySpec implements ValueSelectorWrapperInterface
{
    /**
     * @var ValueSelectorInterface
     *
     * @psalm-readonly
     */
    private $valueSelector;

    /**
     * @param array|\Traversable $array
     *
     * @psalm-param ResultFactoryInterface<SupportedInput> $resultFactory
     * @psalm-param ValueSelectorInterface<SupportedSubject> $valueSelector
     */
    protected function __construct(ResultFactoryInterface $resultFactory, ValueSelectorInterface $valueSelector, $array)
    {
        parent::__construct($resultFactory, $array);

        $this->valueSelector = $valueSelector;
    }

    /**
     * Returns an instance of ValueSelectorInterface.
     *
     * @psalm-return ValueSelectorInterface<SupportedSubject>
     *
     * @psalm-mutation-free
     */
    public function getValueSelector(): ValueSelectorInterface
    {
        return $this->valueSelector;
    }
//
//
//    public function toString(): string
//    {
//        return sprintf(
//            'is %s with %s %s specified',
//            $this->valueSelector->subject(),
//            $this->valueSelector->selectable(),
//            $this->comparator->adjective()
//        );
//    }
//
//    /**
//     * @param mixed    $role
//     */
//    public function toStringInContext(Operator $operator, $role): string
//    {
//        if ($operator instanceof LogicalNot) {
//            return sprintf(
//                'fails to be %s with %s %s specified',
//                $this->valueSelector->subject(),
//                $this->valueSelector->selectable(),
//                $this->comparator->adjective()
//            );
//        }
//
//        return '';
//    }
}

// vim: syntax=php sw=4 ts=4 et:
