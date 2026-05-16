<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-extends \ArrayObject<mixed,mixed>
 */
final class DummyExpectedValues extends \ArrayObject implements ValuesInterface, ValueSelectorWrapperInterface
{
    /**
     * @var ValueSelectorInterface
     */
    private $valueSelector;

    /**
     * @param array|\Traversable $array
     */
    public function __construct(ValueSelectorInterface $valueSelector, $array = [])
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
        return DummyValues::class.':a1a44e79c791a1fe22ac49067eef00b222d10131';
    }

    /**
     * @param array|\Traversable $array
     *
     * @psalm-param array|\Traversable<array-key,mixed> $array
     *
     * @psalm-mutation-free
     */
    public function createActualValues($array = []): ValuesInterface
    {
        return new DummyValues(true, $array);
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
