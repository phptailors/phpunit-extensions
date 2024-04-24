<?php declare(strict_types=1);

use Symplify\MonorepoBuilder\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;
use Tailors\MonorepoBuilder\MostRecentTagResolver;

return
static function (MBConfig $mbConfig) : void {
    $services = $mbConfig->services();
    $services->set(MostRecentTagResolver::class)
             ->autowire()
             ->alias(TagResolverInterface::class, MostRecentTagResolver::class);

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
            'composer/semver' => '^3.0',
            'sebastian/cli-parser' => '>=1.0',
        ],
        ComposerJsonSection::AUTOLOAD_DEV => [
            'psr-4' => [
                'Tailors\\PHPUnit\\Docs\\Behat\\' => 'docs/sphinx/behat/',
            ],
        ],
        ComposerJsonSection::EXTRA => [
            'bamarni-bin' => [
                'bin-links' => false,
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

    $mbConfig->disableDefaultWorkers();
    $mbConfig->workers([
        UpdateReplaceReleaseWorker::class,
        TagVersionReleaseWorker::class,
        PushTagReleaseWorker::class,
    ]);
};
