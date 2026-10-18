<?php

declare(strict_types=1);

namespace Tailors\MonorepoBuilder;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\DependencyUpdater;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Package\PackageNamesProvider;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Utils\VersionUtils;

final class RestoreCurrentMutualDependenciesReleaseWorker implements ReleaseWorkerInterface
{
    /**
     * @var ComposerJsonProvider
     *
     * @readonly
     */
    private $composerJsonProvider;

    /**
     * @var DependencyUpdater
     *
     * @readonly
     */
    private $dependencyUpdater;

    /**
     * @var PackageNamesProvider
     *
     * @readonly
     */
    private $packageNamesProvider;

    /**
     * @var VarsionUtils
     *
     * @readonly
     */
    private $versionUtils;

    public function __construct(
        ComposerJsonProvider $composerJsonProvider,
        DependencyUpdater $dependencyUpdater,
        PackageNamesProvider $packageNamesProvider,
        VersionUtils $versionUtils
    ) {
        $this->composerJsonProvider = $composerJsonProvider;
        $this->dependencyUpdater = $dependencyUpdater;
        $this->packageNamesProvider = $packageNamesProvider;
        $this->versionUtils = $versionUtils;
    }

    public function work(Version $version): void
    {
        $versionInString = $this->getRequiredNextFormat($version);

        $this->dependencyUpdater->updateFileInfosWithPackagesAndVersion(
            $this->composerJsonProvider->getPackagesComposerFileInfos(),
            $this->packageNamesProvider->provide(),
            $versionInString
        );
    }

    public function getDescription(Version $version): string
    {
        $versionInString = $this->getRequiredNextFormat($version);

        return sprintf('Set packages mutual dependencies to "%s" (alias of dev version)', $versionInString);
    }

    private function getRequiredNextFormat(Version $version): string
    {
        if (!$version->hasPreReleaseSuffix()) {
            $version = new Version($version->getVersionString().'-dev');
        }

        return '^'.$this->versionUtils->getNextAliasFormat($version);
    }
}
