<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArraySpec;

use Tailors\PHPUnit\Result\ResultFactoryWrapperInterface;


/**
 * @template-extends \Traversable<array-key, mixed>
 * @template-extends \ArrayAccess<array-key, mixed>
 *
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type SupportedInput = array|\Traversable
 * @template-extends ResultFactoryWrapperInterface<SupportedInput>
 */
interface ArraySpecInterface extends \Traversable, \ArrayAccess, \Countable, ResultFactoryWrapperInterface {}

// vim: syntax=php sw=4 ts=4 et:
