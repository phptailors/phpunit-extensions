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
     * @var bool|\Closure
     *
     * @psalm-var bool|\Closure(mixed):bool
     *
     * @psalm-readonly
     */
    private $supports;

    /**
     * @var bool|\Closure
     *
     * @psalm-var bool|\Closure(mixed,mixed,mixed):bool
     *
     * @psalm-readonly
     */
    private $select;

    /**
     * @var string
     *
     * @psalm-readonly
     */
    private $subject;

    /**
     * @var string
     *
     * @psalm-readonly
     */
    private $selectable;

    /**
     * @param bool|\Closure $supports
     * @param bool|\Closure $select
     *
     * @psalm-param bool|\Closure(mixed):bool             $supports
     * @psalm-param bool|\Closure(mixed,mixed,mixed):bool $select
     */
    public function __construct($supports = false, $select = false, string $subject = '', string $selectable = '')
    {
        $this->supports = $supports;
        $this->select = $select;
        $this->subject = $subject;
        $this->selectable = $selectable;
    }

    /**
     * @param mixed $subject
     *
     * @psalm-mutation-free
     */
    public function supports($subject): bool
    {
        if (is_bool($this->supports)) {
            return $this->supports;
        }

        return call_user_func($this->supports, $subject);
    }

    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $retval
     *
     * @psalm-param array-key $key
     *
     * @psalm-param-out mixed $retval
     *
     * @psalm-mutation-free
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
