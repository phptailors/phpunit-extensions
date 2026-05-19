<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedObjectProperties extends AbstractObjectProperties implements ResultInterface
{
    /**
     * @psalm-mutation-free
     */
    public function actual(): bool
    {
        return false;
    }
}

// vim: syntax=php sw=4 ts=4 et:
