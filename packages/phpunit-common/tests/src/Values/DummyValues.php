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
final class DummyValues extends \ArrayObject implements ValuesInterface
{
    /**
     * @var ?bool
     */
    private $actual;

    /**
     * @param array|object $array
     */
    public function __construct(
        bool $actual,
        $array = [],
        int $flags = 0,
        string $iteratorClass = \ArrayIterator::class
    ) {
        $this->actual = $actual;
        parent::__construct($array, $flags, $iteratorClass);
    }

    /**
     * @psalm-mutation-free
     */
    public function actual(): bool
    {
        return $this->actual;
    }
}

// vim: syntax=php sw=4 ts=4 et:
