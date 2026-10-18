<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-extends \ArrayObject<array-key, mixed>
 */
final class DummyNamedArray extends \ArrayObject implements ExportableNameInterface
{
    /**
     * @psalm-pure
     */
    public static function exportableName(): string
    {
        return 'DummyNamedArray';
    }
}

// vim: syntax=php sw=4 ts=4 et:
