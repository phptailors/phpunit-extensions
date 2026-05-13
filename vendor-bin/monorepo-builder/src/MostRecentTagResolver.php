<?php declare(strict_types=1);

namespace Tailors\MonorepoBuilder;

use PharIo\Version\InvalidVersionException;
use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use PharIo\Version\Version;
use PharIo\Version\VersionConstraintParser;

final class MostRecentTagResolver implements TagResolverInterface
{
    // Gets only tags for current branch.
    private const GIT_TAG = [ 'git', 'tag', '-l', '--sort=v:refname', '--merged', 'HEAD' ];
    // Gets only current branch name
    private const GIT_BRANCH = ['git', 'branch', '--show-current', '--no-color'];

    /**
     * @var ProcessRunner
     */
    private $processRunner;

    public function __construct(ProcessRunner $processRunner)
    {
        $this->processRunner = $processRunner;
    }

    public function resolve(string $gitDirectory): ?string
    {
        $tagList = $this->filterSemverTags(
            $this->parseTags($this->processRunner->run(self::GIT_TAG, $gitDirectory)),
            $this->parseBranch($this->processRunner->run(self::GIT_BRANCH, $gitDirectory))
        );

        return (string)array_pop($tagList) ?: null;
    }

    private function parseBranch(string $commandResult): string
    {
        $branch = trim($commandResult);

        // Remove all "\r" chars in case the CLI env like the Windows OS.
        // Otherwise (ConEmu, git bash, mingw cli, e.g.), leave as is.
        $branch = str_replace("\r", '', $branch);

        return (string)$branch;
    }

    /**
     * @return string[]
     */
    private function parseTags(string $commandResult): array
    {
        $tags = trim($commandResult);

        // Remove all "\r" chars in case the CLI env like the Windows OS.
        // Otherwise (ConEmu, git bash, mingw cli, e.g.), leave as is.
        $tags = str_replace("\r", '', $tags);

        return explode("\n", $tags);
    }

    /**
     * @param string[] $tagList
     * @return string[]
     */
    private function filterSemverTags(array $tagList, string $branch): array
    {
        // Only work with branches named "[v]maj.min" or "maj.min.x"
        if (!preg_match('/^v?(?<majmin>[0-9]+\.[0-9]+)(?:\.x)?$/', $branch, $matches, PREG_UNMATCHED_AS_NULL)) {
            return [];
        }

        if (null === ($majmin = $matches['majmin'] ?? null)) {
            return [];
        }


        $parser = new VersionConstraintParser();
        $constraint = $parser->parse("~{$majmin}.0");

        return array_filter($tagList, function ($tag) use ($constraint) {
            try {
                $version = new Version($tag);
            } catch(InvalidVersionException $e) {
                return false;
            }

            return $constraint->complies($version);
        });
    }
}
