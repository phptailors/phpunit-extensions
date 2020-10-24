<?php declare(strict_types=1);


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
/**
 * @psalm-param \Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $container
 */
static function ($container) : void {
    $parameters = $container->parameters();

    $top = __dir__;
    $packagesSubdirsBase = 'packages'; // no trailing slashes here!

    $parameters->set(Option::PACKAGE_DIRECTORIES, [
        $top.'/'.$packagesSubdirsBase,
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
            'symplify/monorepo-builder' => '^8.0.1',
            'phpunit/phpunit' => '>=9.3',
            'behat/behat' => '^3.4',
            'psy/psysh' => 'dev-master',
        ],
        'autoload-dev' => [
            'psr-4' => [
                'Tailors\\PHPUnit\\Docs\\Behat\\' => 'docs/sphinx/behat/',
            ],
        ],
    ]);

    $packagesComposerJsonFiles = glob($top.'/'.$packagesSubdirsBase.'/*/composer.json');
    $packagesDirs  = \array_map('dirname', $packagesComposerJsonFiles);
    $packages = \array_map('basename', $packagesDirs);
    $packagesSubdirs = \preg_replace('/^/', $packagesSubdirsBase.'/', $packages);

    $defaultSplitRepositoryBase = 'file://'.$top.'/build/monorepo-split/repositories/phptailors';
    $parameters->set('default_split_repository_base', $defaultSplitRepositoryBase);
    $splitRepositoryBase = '%env(default:default_split_repository_base:MONOREPO_SPLIT_REPO_BASE)%/';

    $packageRepos = \preg_replace('/^(.+)$/', $splitRepositoryBase.'\1.git', $packages);
    $directoriesToRepositories = array_combine($packagesSubdirs, $packageRepos);

    $parameters->set(Option::DIRECTORIES_TO_REPOSITORIES, $directoriesToRepositories);
};
