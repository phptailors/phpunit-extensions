<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ValueSelector;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-implements ValueSelectorInterface<mixed>
 */
final class DummyValueSelector implements ValueSelectorInterface
{
    /**
     * @param bool|\Closure $supports
     * @param bool|\Closure $select
     *
     * @psalm-param bool|Closure(mixed):bool             $supports
     * @psalm-param bool|Closure(mixed,mixed,mixed):bool $select
     */
    public function __construct(
        private $supports = false,
        private $select = false,
        private readonly string $subject = '',
        private readonly string $selectable = ''
    ) {}

    /**
     * @param mixed $input
     */
    public function supports($input): bool
    {
        if (is_bool($this->supports)) {
            return $this->supports;
        }

        return call_user_func($this->supports, $input);
    }

    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $retval
     *
     * @psalm-param array-key $key
     *
     * @psalm-param-out mixed $retval
     */
    public function select($subject, $key, &$retval): bool
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
