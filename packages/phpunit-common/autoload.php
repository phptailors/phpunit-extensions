<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

use Composer\InstalledVersions;
use Composer\Semver\VersionParser;


if (InstalledVersions::isInstalled('sebastian/exporter') && InstalledVersions::satisfies(new VersionParser, 'sebastian/exporter', '^4')) {
    require_once __dir__ . '/compat/exporter4/AbstractExporter.php';
} else {
    require_once __dir__ . '/compat/exporter5/AbstractExporter.php';
}

// vim: syntax=php sw=4 ts=4 et:
