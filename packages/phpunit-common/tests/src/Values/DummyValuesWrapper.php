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
 */
final class DummyValuesWrapper implements ValuesWrapperInterface
{
    public function __construct(private ValuesInterface $values) {}

    public function getValues(): ValuesInterface
    {
        return $this->values;
    }
}
// vim: syntax=php sw=4 ts=4 et:
