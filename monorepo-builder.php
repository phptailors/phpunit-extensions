<?php declare(strict_types=1);


use Symplify\MonorepoBuilder\ValueObject\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

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
static function (ContainerConfigurator $container) : void {
    $parameters = $container->parameters();

    $parameters->set(Option::PACKAGE_DIRECTORIES, [
        __dir__.'/packages',
    ]);

    $parameters->set(Option::PACKAGE_DIRECTORIES_EXCLUDES, [
        'vendor-bin',
    ]);

    $parameters->set(Option::SECTION_ORDER, [
        'name',
        'type',
        'description',
        'license',
        'keywords',
        'homepage',
        'support',
        'authors',
        'minimum-stability',
        'prefer-stable',
        'bin',
        'require',
        'require-dev',
        'autoload',
        'autoload-dev',
        'repositories',
        'conflict',
        'replace',
        'provide',
        'scripts',
        'suggest',
        'config',
        'extra',
    ]);


    $parameters->set(Option::DATA_TO_APPEND, [
        'require-dev' => [
            'bamarni/composer-bin-plugin' => '^1.8',
            'symplify/monorepo-builder' => '^9.0.11',
        ],
        'autoload-dev' => [
            'psr-4' => [
                'Tailors\\PHPUnit\\Docs\\Behat\\' => 'docs/sphinx/behat/',
            ],
        ],
        'extra' => [
            'bamarni-bin' => [
                'bin-links' => true,
                'target-directory' => 'vendor-bin',
                'forward-command' => false
            ],
        ],
    ]);
};
