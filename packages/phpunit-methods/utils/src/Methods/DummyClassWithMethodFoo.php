<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Methods;

class DummyClassWithMethodFoo
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     *
     * @codeCoverageIgnore
     */
    public function foo(): void
    {
        // empty
    }
}
