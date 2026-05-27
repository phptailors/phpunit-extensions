<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArraySpec;

use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;


/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template SupportedSubject
 *
 * @template-implements ValueSelectorWrapperInterface<SupportedSubject>
 * @template-extends \ArrayObject<array-key,mixex>
 */
final class DummyArraySelectionOnly extends \ArrayObject implements ValueSelectorWrapperInterface
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
     * @psalm-param ValueSelectorInterface<SupportedSubject> $valueSelector
     */
    public function __construct(ValueSelectorInterface $valueSelector, $array)
    {
        $this->valueSelector = $valueSelector;

        if (!is_array($array)) {
            $array = iterator_to_array($array);
        }

        parent::__construct($array);
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
