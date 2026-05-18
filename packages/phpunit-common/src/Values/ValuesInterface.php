<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\Result\ResultInterface;

/**
 * An array of values.
 *
 * @template-extends \Traversable<array-key, mixed>
 * @template-extends \ArrayAccess<array-key, mixed>
 *
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
interface ValuesInterface extends \Traversable, \ArrayAccess, \Countable, ResultInterface
{
    /**
     * @param array|\Traversable $array
     */
    public function createActualValues($array = []): ValuesInterface;
}

// vim: syntax=php sw=4 ts=4 et:
