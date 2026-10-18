<?php

declare(strict_types=1);

namespace Tailors\MonorepoBuilder;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\ComposerJsonManipulator\FileSystem\JsonFileManager;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Exception\MissingComposerJsonException;
use Symplify\MonorepoBuilder\Utils\VersionUtils;

final class RestoreReplaceReleaseWorker implements ReleaseWorkerInterface
{
    /**
     * @var ComposerJsonProvider
     *
     * @readonly
     */
    private $composerJsonProvider;

    /**
     * @var JsonFileManager
     *
     * @readonly
     */
    private $jsonFileManager;

    /**
     * @var VersionUtils
     *
     * @readonly
     */
    private $versionUtils;

    public function __construct(
        ComposerJsonProvider $composerJsonProvider,
        JsonFileManager $jsonFileManager,
        VersionUtils $versionUtils
    ) {
        $this->composerJsonProvider = $composerJsonProvider;
        $this->jsonFileManager = $jsonFileManager;
        $this->versionUtils = $versionUtils;
    }

    public function work(Version $version): void
    {
        $rootComposerJson = $this->composerJsonProvider->getRootComposerJson();

        $replace = $rootComposerJson->getReplace();

        $packageNames = $this->composerJsonProvider->getPackageNames();

        $newReplace = [];
        foreach (array_keys($replace) as $package) {
            if (! in_array($package, $packageNames, true)) {
                continue;
            }

            $newReplace[$package] = $this->getRestoreAliasFormat($version);
        }

        if ($replace === $newReplace) {
            return;
        }

        $rootComposerJson->setReplace($newReplace);

        $rootFileInfo = $rootComposerJson->getFileInfo();
        if (null === $rootFileInfo) {
            throw new MissingComposerJsonException();
        }

        $this->jsonFileManager->printJsonToFileInfo($rootComposerJson->getJsonArray(), $rootFileInfo);
    }

    public function getDescription(Version $version): string
    {
        return sprintf('Restore "replace" version (%s) in "composer.json"', $this->getRestoreAliasFormat($version));
    }

    private function getRestoreAliasFormat(Version $version): string
    {
        if (!$version->hasPreReleaseSuffix()) {
            $version = new Version($version->getVersionString().'-dev');
        }

        return $this->versionUtils->getNextAliasFormat($version);
    }
}
