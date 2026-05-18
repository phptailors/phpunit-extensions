<?php

declare(strict_types=1);

namespace Tailors\MonorepoBuilder;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\DependencyUpdater;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Package\PackageNamesProvider;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Utils\VersionUtils;

final class SetCurrentMutualDependenciesReleaseWorker implements ReleaseWorkerInterface
{
    /**
     * @var VersionUtils
     *
     * @readonly
     */
    private $versionUtils;

    /**
     * @var DependencyUpdater
     *
     * @readonly
     */
    private $dependencyUpdater;

    /**
     * @var ComposerJsonProvider
     *
     * @readonly
     */
    private $composerJsonProvider;

    /**
     * @var PackageNamesProvider
     *
     * @readonly
     */
    private $packageNamesProvider;

    public function __construct(
        VersionUtils $versionUtils,
        DependencyUpdater $dependencyUpdater,
        ComposerJsonProvider $composerJsonProvider,
        PackageNamesProvider $packageNamesProvider
    ) {
        $this->versionUtils = $versionUtils;
        $this->dependencyUpdater = $dependencyUpdater;
        $this->composerJsonProvider = $composerJsonProvider;
        $this->packageNamesProvider = $packageNamesProvider;
    }

    public function work(Version $version): void
    {
        $versionInString = $this->getRequiredVersionString($version);

        $this->dependencyUpdater->updateFileInfosWithPackagesAndVersion(
            $this->composerJsonProvider->getPackagesComposerFileInfos(),
            $this->packageNamesProvider->provide(),
            $versionInString
        );

        // give time to propagate values before commit
        sleep(1);
    }

    public function getDescription(Version $version): string
    {
        $versionInString = $this->getRequiredVersionString($version);

        return sprintf('Set packages mutual dependencies to "%s" version', $versionInString);
    }

    private function getRequiredVersionString(Version $version): string
    {
        return '~'.ltrim($this->versionUtils->getRequiredFormat($version), '^');
    }
}
