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
    public function supports(mixed $subject): bool
    {
        return false;
    }

    /**
     * @psalm-param array-key $key
     *
     * @psalm-param-out mixed $retval
     */
    public function select(mixed $subject, mixed $key, mixed &$retval): bool
    {
        return false;
    }

    public function subject(): string
    {
        return '';
    }

    public function selectable(): string
    {
        return '';
    }
}
