<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Recursive;

use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveSelectorState
{
    public function __construct(public readonly mixed $subject, public array|ValuesInterface $result) {}
}

// vim: syntax=php sw=4 ts=4 et:
