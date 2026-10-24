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

// @codeCoverageIgnoreStart
if (method_exists(TestCase::class, 'expectExceptionMessageIsOrContains')) {
    class_alias(ExpectExceptionMessageIsOrContainsTrait_Empty::class, __NAMESPACE__.'\ExpectExceptionMessageIsOrContainsTrait');
} else {
    class_alias(ExpectExceptionMessageIsOrContainsTrait_NonEmpty::class, __NAMESPACE__.'\ExpectExceptionMessageIsOrContainsTrait');
}
// @codeCoverageIgnoreEnd
