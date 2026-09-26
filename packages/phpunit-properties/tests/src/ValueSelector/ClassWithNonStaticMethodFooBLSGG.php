<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ValueSelector;

final class ClassWithNonStaticMethodFooBLSGG
{
    public function foo()
    {
        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd
}
