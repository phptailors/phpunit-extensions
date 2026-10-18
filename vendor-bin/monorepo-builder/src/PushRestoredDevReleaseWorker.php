<?php

declare(strict_types=1);

namespace Tailors\MonorepoBuilder;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\Utils\VersionUtils;

final class PushRestoredDevReleaseWorker implements ReleaseWorkerInterface
{
    /**
     * @var ProcessRunner
     *
     * @readonly
     */
    private $processRunner;

    /**
     * @var VersionUtils
     *
     * @readonly
     */
    private $versionUtils;

    public function __construct(
        ProcessRunner $processRunner,
        VersionUtils $versionUtils,
    ) {
        $this->processRunner = $processRunner;
        $this->versionUtils = $versionUtils;
    }

    public function work(Version $version): void
    {
        $versionInString = $this->getVersionDev($version);

        $gitAddCommitCommand = sprintf(
            'git add . && git commit --allow-empty -m "re-open %s" && git push"',
            $versionInString,
        );

        $this->processRunner->run($gitAddCommitCommand);
    }

    public function getDescription(Version $version): string
    {
        $versionInString = $this->getVersionDev($version);

        return sprintf('Push "%s" to remote repository (on current git branch)', $versionInString);
    }

    private function getVersionDev(Version $version): string
    {
        if (!$version->hasPreReleaseSuffix()) {
            $version = new Version($version->getVersionString().'-dev');
        }

        return $this->versionUtils->getNextAliasFormat($version);
    }
}
