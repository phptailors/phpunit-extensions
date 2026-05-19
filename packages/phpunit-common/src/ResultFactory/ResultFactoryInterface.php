<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ResultFactory;

use Tailors\PHPUnit\Common\SupportInterface;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template SupportedInput
 *
 * @template-extends SupportInterface<SupportedInput>
 */
interface ResultFactoryInterface extends SupportInterface
{
    /**
     * @param mixed $input
     *
     * @throws InvalidArgumentException
     *
     * @psalm-assert SupportedInput $input
     */
    public function getResult(bool $actual, $input): ResultInterface;
}

// vim: syntax=php sw=4 ts=4 et:
