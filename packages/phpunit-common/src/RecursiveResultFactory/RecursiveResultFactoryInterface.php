<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use Tailors\PHPUnit\ArraySpec\ArraySpecInterface;
use Tailors\PHPUnit\Result\ResultFactoryInterface;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-extends ResultFactoryInterface<array|ArraySpecInterface>
 */
interface RecursiveResultFactoryInterface extends ResultFactoryInterface
{
}

// vim: syntax=php sw=4 ts=4 et:
