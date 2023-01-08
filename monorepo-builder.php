<?php declare(strict_types=1);

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\ValueObject\Option;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// USAGE EXAMPLES:
//
// 1. Split onto repositories under "build/monorepo-split/repositories/phptailors/"
//
//      vendor/bin/monorepo-builder split
//
//    Bare repositories are required to be present uner this base path before the
//    split is attempted. To initialize these repositories run
//
//      util/initialize-split-repositories.sh
//
// 2. Split onto repositories under git@github.com:phptailors/
//
//      MONOREPO_SPLIT_REPO_BASE='git@github.com:phptailors' vendor/bin/monorepo-builder split
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

return
static function (MBConfig $mbConfig) : void {
    $mbConfig->packageDirectories([
        __dir__ . '/packages'
    ]);

    $mbConfig->packageDirectoriesExcludes([
        'vendor-bin'
    ]);

    // for "merge" command
    $mbConfig->dataToAppend([
        ComposerJsonSection::REQUIRE_DEV => [
            'bamarni/composer-bin-plugin' => '^1.8',
            'symplify/monorepo-builder' => '^11.1',
        ],
        ComposerJsonSection::AUTOLOAD_DEV => [
            'psr-4' => [
                'Tailors\\PHPUnit\\Docs\\Behat\\' => 'docs/sphinx/behat/',
            ],
        ],
        ComposerJsonSection::EXTRA => [
            'bamarni-bin' => [
                'bin-links' => true,
                'target-directory' => 'vendor-bin',
                'forward-command' => false,
            ],
        ],
        // not handled by monorepo-builder, just for reference
        ComposerJsonSection::CONFIG => [
            "allow-plugins" => [
                "bamarni/composer-bin-plugin" => true,
            ]
        ],
    ]);
};
