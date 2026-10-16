<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Result;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class DummyResult implements ResultInterface
{
    /**
     * @var bool
     *
     * @psalm-readonly
     */
    private $actual;

    public function __construct(bool $actual)
    {
        $this->actual = $actual;
    }

    /**
     * @psalm-mutation-free
     */
    public function actual(): bool
    {
        return $this->actual;
    }
}

// vim: syntax=php sw=4 ts=4 et:
