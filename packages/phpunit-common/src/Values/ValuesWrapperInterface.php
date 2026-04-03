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
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
interface ValuesWrapperInterface
{
    /**
     * Returns an instance of ValuesInterface.
     */
    public function getValues(): ValuesInterface;
}

// vim: syntax=php sw=4 ts=4 et:
