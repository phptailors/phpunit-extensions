<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArraySpec;

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
 * @psalm-import-type ArrayLike from \Tailors\PHPUnit\Common\TypesInterface
 *
 * @template-extends AbstractArraySpec<SupportedInput>
 *
 * @template-implements ValueSelectorWrapperInterface<SupportedSubject>
 */
abstract class AbstractArraySelectionSpec extends AbstractArraySpec implements ValueSelectorWrapperInterface
{
    /**
     * @var ValueSelectorInterface
     *
     * @psalm-var ValueSelectorInterface<SupportedSubject>
     *
     * @psalm-readonly
     */
    private $valueSelector;

    /**
     * @psalm-param ResultFactoryInterface<SupportedInput>   $resultFactory
     * @psalm-param ValueSelectorInterface<SupportedSubject> $valueSelector
     * @psalm-param ArrayLike                                $array
     */
    protected function __construct(ResultFactoryInterface $resultFactory, ValueSelectorInterface $valueSelector, iterable $array)
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
}

// vim: syntax=php sw=4 ts=4 et:
