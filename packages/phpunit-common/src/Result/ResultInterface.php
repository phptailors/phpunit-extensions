<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Result;

use Tailors\PHPUnit\Common\TagInterface;

/**
 * A constraint evaluation result (either expected, or actual).
 *
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
interface ResultInterface extends TagInterface
{
    /**
     * Returns true if this object represents actual result (as opposite to expected one).
     */
    public function actual(): bool;
}

// vim: syntax=php sw=4 ts=4 et:
