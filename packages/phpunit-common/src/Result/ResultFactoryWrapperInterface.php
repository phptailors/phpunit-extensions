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
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template SupportedInput
 */
interface ResultFactoryWrapperInterface
{
    /**
     * Returns an instance of ResultFactoryInterface.
     *
     * @psalm-return ResultFactoryInterface<SupportedInput>
     */
    public function getResultFactory(): ResultFactoryInterface;
}

// vim: syntax=php sw=4 ts=4 et:
