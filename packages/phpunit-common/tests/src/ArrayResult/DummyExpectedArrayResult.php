<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

use Tailors\PHPUnit\Result\ResultInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorWrapperInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template SupportedSubject
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 *
 * @template-extends \ArrayObject<mixed,mixed>
 *
 * @template-implements ValueSelectorWrapperInterface<SupportedSubject>
 */
final class DummyExpectedArrayResult extends \ArrayObject implements ResultInterface, ValueSelectorWrapperInterface
{
    /**
     * @var ValueSelectorInterface
     */
    private $valueSelector;

    /**
     * @psalm-param ArrayLike $array
     */
    public function __construct(ValueSelectorInterface $valueSelector, iterable $array = [])
    {
        $this->valueSelector = $valueSelector;

        if (!is_array($array)) {
            $array = iterator_to_array($array);
        }

        parent::__construct($array);
    }

    /**
     * @psalm-mutation-free
     */
    public function actual(): bool
    {
        return false;
    }

    /**
     * @psalm-return non-empty-string
     *
     * @psalm-mutation-free
     */
    public function tag(): string
    {
        return DummyArrayResult::class.':a1a44e79c791a1fe22ac49067eef00b222d10131';
    }

    /**
     * @psalm-mutation-free
     */
    public function getValueSelector(): ValueSelectorInterface
    {
        return $this->valueSelector;
    }
}

// vim: syntax=php sw=4 ts=4 et:
