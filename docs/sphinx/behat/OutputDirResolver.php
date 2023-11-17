<?php declare(strict_types=1);

namespace Tailors\PHPUnit\Docs\Behat;

use \Composer\InstalledVersions;
use \Composer\Semver\Semver;

final class OutputDirResolver
{
    /**
     * @var array|null $defaultConfig
     */
    private static $defaultConfig = null;

    private static $idVersionMap = [
        'php' => [
            0x020 => '>= 8.2',
        ],

        'phpunit/phpunit' => [
            0x020 => '>= 10.0',
        ],

        'sebastian/exporter' => [
            0x020 => '>= 5.1',
        ]
    ];

    /**
     * @var array $config
     */
    private $config;

    public function __construct(array $config = null)
    {
        if (null !== $config) {
            self::validateConfig($config, __method__, 1, '$config');
            $this->config = array_replace_recursive(self::defaultConfig(), $config);
        } else {
            $this->config = self::defaultConfig();
        }
    }

    public function config(): array
    {
        return $this->config;
    }

    public function path(?string $file = null): string
    {
        $path = $this->outputsBasePath().'/'.$this->compatSlug().($file ? '/'.$file : '');

        if (null !== ($base = $this->config['relative'] ?? null)) {
            if ($base && !is_string($base)) {
                $base = '.';
            }
            $path = Path::relative($path, $base);
        }
        return $path;
    }

    private function outputsBasePath(): string
    {
        return $this->basePath().'/'.$this->outputsDirBasename();
    }

    private function basePath(): string
    {
        return realpath(__DIR__.'/..');
    }

    private function outputsDirBasename(): string
    {
        return '_outputs';
    }

    private function compatSlug(): string
    {
        $php = $this->idBySubject('php');
        $phpunit = $this->idBySubject('phpunit/phpunit');
        $exporter = $this->idBySubject('sebastian/exporter');

        return sprintf('%03x-%03x-%03x', $php, $phpunit, $exporter);
    }

    private function installedVersions(): array
    {
        return [
            'php' => sprintf('%d.%d.%d', PHP_MAJOR_VERSION, PHP_MINOR_VERSION, PHP_RELEASE_VERSION),
            'phpunit/phpunit' => InstalledVersions::getVersion('phpunit/phpunit'),
            'sebastian/exporter' => InstalledVersions::getVersion('sebastian/exporter'),
        ];
    }

    private function defaultConfig(): array
    {
        if (null === self::$defaultConfig) {
            self::$defaultConfig = [
                'versions' => self::installedVersions(),
            ];
        }
        return self::$defaultConfig;
    }

    private function idBySubject(string $subject): int
    {
        $version = $this->config['versions'][$subject];

        foreach (self::$idVersionMap[$subject] as $id => $versionSpec) {
            if (Semver::satisfies($version, $versionSpec)) {
                return $id;
            }
        }

        return 0x000;
    }

    private static function validateConfig(array $config, string $method, int $argno, string $argname): void
    {
        if (isset($config['versions'])) {
            self::validateVersions($config['versions'], $method, $argno, $argname);
        }
    }

    private static function validateVersions($versions, string $method, int $argno, string $argname): void
    {
        if (!is_array($versions)) {
            $message = sprintf(
                'Invalid argument %2$d (%3$s) to %1$s(): %3$s["versions"] is not an array',
                $method,
                $argno,
                $argname
            );
            throw new \InvalidArgumentException($message);
        }

        foreach ($versions as $package => $version) {
            if (!is_string($version)) {
                $message = sprintf(
                    'Invalid argument %2$d (%3$s) to %1$s(): %3$s["versions"]["%4$s"] is not a string',
                    $method,
                    $argno,
                    $argname,
                    $package
                );
                throw new \InvalidArgumentException($message);
            }
        }
    }
}
