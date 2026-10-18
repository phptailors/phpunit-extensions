<?php

declare(strict_types=1);

namespace Tailors\MonorepoBuilder;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\DevMasterAliasUpdater;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Utils\VersionUtils;

final class UpdateBranchAliasReleaseWorker implements ReleaseWorkerInterface
{
    /**
     * @var DevMasterAliasUpdater
     *
     * @readonly
     */
    private $devMasterAliasUpdater;

    /**
     * @var ComposerJsonProvider
     *
     * @readonly
     */
    private $composerJsonProvider;

    /**
     * @var VersionUtils
     *
     * @readonly
     */
    private $versionUtils;

    public function __construct(
        DevMasterAliasUpdater $devMasterAliasUpdater,
        ComposerJsonProvider $composerJsonProvider,
        VersionUtils $versionUtils
    ) {
        $this->devMasterAliasUpdater = $devMasterAliasUpdater;
        $this->composerJsonProvider = $composerJsonProvider;
        $this->versionUtils = $versionUtils;
    }

    public function work(Version $version): void
    {
        $nextAlias = $this->getRestoreAliasFormat($version);

        $this->devMasterAliasUpdater->updateFileInfosWithAlias(
            $this->composerJsonProvider->getPackagesComposerFileInfos(),
            $nextAlias
        );
    }

    public function getDescription(Version $version): string
    {
        $nextAlias = $this->getRestoreAliasFormat($version);

        return sprintf('Set branch alias "%s" to all packages', $nextAlias);
    }

    private function getRestoreAliasFormat(Version $version): string
    {
        if (!$version->hasPreReleaseSuffix()) {
            $version = new Version($version->getVersionString().'-dev');
        }

        return $this->versionUtils->getNextAliasFormat($version);
    }
}
