<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArraySpec;

use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\ResultFactory\ResultFactoryInterface;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;
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
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @template-implements ResultFactoryWrapperInterface<SupportedInput>
 * @template-implements ValueSelectorWrapperInterface<SupportedSubject>
 *
 * @template-extends \ArrayObject<array-key, mixed>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class DummyResultFactoryAndValueSelectorWrapper extends \ArrayObject implements ResultFactoryWrapperInterface, ValueSelectorWrapperInterface
{
    /**
     * @var ResultFactoryInterface
     *
     * @psalm-param ResultFactoryInterface<SupportedInput>
     *
     * @psalm-readonly
     */
    private $resultFactory;

    /**
     * @var ValueSelectorInterface
     *
     * @psalm-readonly
     */
    private $valueSelector;

    /**
     * @psalm-param ResultFactoryInterface<SupportedInput>   $resultFactory
     * @psalm-param ValueSelectorInterface<SupportedSubject> $valueSelector
     * @psalm-param ArrayLike                                $array
     */
    public function __construct(ResultFactoryInterface $resultFactory, ValueSelectorInterface $valueSelector, iterable $array)
    {
        $this->resultFactory = $resultFactory;
        $this->valueSelector = $valueSelector;

        if (!is_array($array)) {
            $array = iterator_to_array($array);
        }

        parent::__construct($array);
    }

    /**
     * @psalm-return ResultFactoryInterface<SupportedInput>
     *
     * @psalm-mutation-free
     */
    final public function getResultFactory(): ResultFactoryInterface
    {
        return $this->resultFactory;
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
}

// vim: syntax=php sw=4 ts=4 et:
