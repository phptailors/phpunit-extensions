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


/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template SupportedInput
 * @psalm-template SupportedSubject
 *
 * @template-extends AbstractArraySelectionSpec<SupportedInput, SupportedSubject>
 */
final class DummyArraySelection extends AbstractArraySelectionSpec
{
    /**
     * @param array|\Traversable $array
     *
     * @psalm-param ResultFactoryInterface<SupportedInput> $resultFactory
     * @psalm-param ValueSelectorInterface<SupportedSubject> $valueSelector
     */
    public function __construct(ResultFactoryInterface $resultFactory, ValueSelectorInterface $valueSelector, $array)
    {
        parent::__construct($resultFactory, $valueSelector, $array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
