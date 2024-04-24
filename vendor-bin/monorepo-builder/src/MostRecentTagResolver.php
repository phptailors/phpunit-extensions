<?php declare(strict_types=1);

namespace Tailors\MonorepoBuilder;

use PharIo\Version\InvalidVersionException;
use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use PharIo\Version\Version;

final class MostRecentTagResolver implements TagResolverInterface
{
    // Gets only tags for current branch.
    private const COMMAND = [ 'git', 'tag', '-l', '--sort=committerdate', '--merged', 'HEAD' ];

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
            $this->parseTags($this->processRunner->run(self::COMMAND, $gitDirectory))
        );

        return (string)array_pop($tagList) ?: null;
    }

    /**
     * @return string[]
     */
    private function parseTags(string $commandResult): array
    {
        $tags = trim($commandResult);

        // Remove all "\r" chars in case the CLI env like the Windows OS.
        // Otherwise (ConEmu, git bash, mingw cli, e.g.), leave as is.
        $normalizedTags = str_replace("\r", '', $tags);

        return explode("\n", $normalizedTags);
    }

    /**
     * @param string[] $tagList
     * @return string[]
     */
    private function filterSemverTags(array $tagList): array
    {
        return array_filter($tagList, function ($tag) {
            try {
                new Version($tag);
            } catch(InvalidVersionException $e) {
                return false;
            }
            return true;
        });
    }
}
