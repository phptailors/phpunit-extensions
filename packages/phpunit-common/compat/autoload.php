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
use Composer\Autoload\ClassLoader;


$loader = new ClassLoader();

if (InstalledVersions::isInstalled('sebastian/exporter') && InstalledVersions::satisfies(new VersionParser, 'sebastian/exporter', '^4')) {
    $loader->addPsr4('Tailors\\PHPUnit\\Exporter\\',  __dir__ . '/Exporter4/');
} else {
    $loader->addPsr4('Tailors\\PHPUnit\\Exporter\\', __dir__ . '/Exporter5/');
}

$loader->register();

// vim: syntax=php sw=4 ts=4 et:
