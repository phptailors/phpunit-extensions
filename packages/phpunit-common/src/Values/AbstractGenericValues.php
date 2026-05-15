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
 * An array of expected or actual values (generic).
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
abstract class AbstractGenericValues extends AbstractValues
{
    /**
     * @psalm-param ?non-empty-string $tag
     */
    final public function __construct(array|\Traversable $array = [], private readonly ?string $tag = null)
    {
        parent::__construct($array);
    }

    /**
     * @psalm-return non-empty-string
     */
    final public function familyName(): string
    {
        return __NAMESPACE__.'\GenericValues';
    }

    /**
     * @psalm-return non-empty-string
     */
    final public function tag(): string
    {
        return $this->tag ?? $this->familyTag();
    }

    final public function createActualValues(array|\Traversable $array = []): ValuesInterface
    {
        return new ActualValues($array, $this->tag);
    }

    final protected function fallbackFamilyString(): string
    {
        return 'b431aa5424c80003a46c769389f28d0bcde7bf21';
    }
}

// vim: syntax=php sw=4 ts=4 et:
