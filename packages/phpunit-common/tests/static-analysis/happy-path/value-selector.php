<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\ValueSelector;

use Tailors\PHPUnit\Values\DummyValueSelector;
use Tailors\PHPUnit\Values\DummyValueSelectorWrapper;
use Tailors\PHPUnit\Values\ValueSelectorInterface;

function consume(): ValueSelectorInterface
{
    $wrapper = new DummyValueSelectorWrapper(new DummyValueSelector());

    return $wrapper->getValueSelector();
}

// vim: syntax=php sw=4 ts=4 et:
