<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

final class DummyValueSelector implements ValueSelectorInterface
{
    /**
     * @psalm-param bool|\Closure(mixed):bool             $supports
     * @psalm-param bool|\Closure(mixed,mixed,mixed):bool $select
     */
    public function __construct(
        private readonly bool|\Closure $supports = false,
        private readonly bool|\Closure $select = false,
        private readonly string $subject = '',
        private readonly string $selectable = ''
    ) {}

    /**
     * @psalm-mutation-free
     */
    public function supports(mixed $subject): bool
    {
        if (is_bool($this->supports)) {
            return $this->supports;
        }

        return call_user_func($this->supports, $subject);
    }

    /**
     * @psalm-param array-key $key
     *
     * @psalm-param-out mixed $retval
     *
     * @psalm-mutation-free
     */
    public function select(mixed $subject, mixed $key, mixed &$retval): bool
    {
        if (is_bool($this->select)) {
            return $this->select;
        }

        return call_user_func_array($this->select, [$subject, $key, &$retval]);
    }

    /**
     * @psalm-mutation-free
     */
    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * @psalm-mutation-free
     */
    public function selectable(): string
    {
        return $this->selectable;
    }
}
