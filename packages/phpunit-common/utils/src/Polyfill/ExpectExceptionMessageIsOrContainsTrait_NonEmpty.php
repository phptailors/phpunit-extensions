<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Polyfill;

use PHPUnit\Framework\TestCase;

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-require-extends TestCase
 *
 * @var TestCase $__phpactor__workaround__unused_import__TestCase
 */
trait ExpectExceptionMessageIsOrContainsTrait_NonEmpty
{
    /** @psalm-suppress DuplicateClass */
    protected function expectExceptionMessageIsOrContains(string $message): void
    {
        $this->expectExceptionMessage($message);
    }
}
