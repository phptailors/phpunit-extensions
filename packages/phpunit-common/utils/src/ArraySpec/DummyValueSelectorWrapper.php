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
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Predicate\ComparatorAwareComplementInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template SupportedSubject
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @template-implements ValueSelectorWrapperInterface<SupportedSubject>
 *
 * @template-extends \ArrayObject<array-key,mixed>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class DummyValueSelectorWrapper extends \ArrayObject implements ValueSelectorWrapperInterface, ComparatorAwareComplementInterface
{
    /**
     * @var ValueSelectorInterface
     *
     * @psalm-readonly
     */
    private $valueSelector;

    /**
     * @psalm-param ValueSelectorInterface<SupportedSubject> $valueSelector
     * @psalm-param ArrayLike                                $array
     */
    public function __construct(ValueSelectorInterface $valueSelector, iterable $array)
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

    public function complement(ComparatorInterface $comparator): string
    {
        return sprintf('%s with %s %s the specified ones', $this->valueSelector->subject(), $this->valueSelector->selectable(), $comparator->adjective());
    }
}

// vim: syntax=php sw=4 ts=4 et:
