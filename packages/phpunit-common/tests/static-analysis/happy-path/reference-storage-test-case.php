<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\ReferenceStorageTestCase;

use Tailors\PHPUnit\Common\ReferenceStorage;

function consume(): ReferenceStorage
{
    $storage = new ReferenceStorage();
    $count = $storage->count();

    if (0 === $count) {
        $storage->add($count);
    }

    if ($storage->contains($count)) {
        $storage->remove($count);
    }

    return $storage;
}

// vim: syntax=php sw=4 ts=4 et:
