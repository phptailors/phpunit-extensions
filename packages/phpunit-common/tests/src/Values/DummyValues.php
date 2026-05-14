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
    public function __construct(private readonly bool $actual, array|\Traversable $array = [])
    {
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
        return $this->actual;
    }

    /**
     * @psalm-return non-empty-string
     *
     * @psalm-mutation-free
     */
    public function tag(): string
    {
        return self::class.':a1a44e79c791a1fe22ac49067eef00b222d10131';
    }

    /**
     * @psalm-param array|\Traversable<array-key,mixed> $array
     *
     * @psalm-mutation-free
     */
    public function createActualValues(array|\Traversable $array = []): ValuesInterface
    {
        return new self(true, $array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
