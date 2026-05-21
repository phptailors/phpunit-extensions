<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

use Tailors\PHPUnit\ArrayResult\ArrayResultInterface;
use Tailors\PHPUnit\Selector\ValueSelectorInterface;
use Tailors\PHPUnit\Selector\ValueSelectorWrapperInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-extends \ArrayObject<mixed,mixed>
 */
final class DummyExpectedArrayResult extends \ArrayObject implements ArrayResultInterface, ValueSelectorWrapperInterface
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
        return DummyArrayResult::class.':a1a44e79c791a1fe22ac49067eef00b222d10131';
    }

    /**
     * @param array|\Traversable $array
     *
     * @psalm-param array|\Traversable<array-key,mixed> $array
     *
     * @psalm-mutation-free
     */
    public function createActualValues($array = []): ArrayResultInterface
    {
        return new DummyArrayResult(true, $array);
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
