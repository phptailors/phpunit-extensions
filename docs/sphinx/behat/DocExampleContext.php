<?php declare(strict_types=1);


namespace Tailors\PHPUnit\Docs\Behat;

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;

class DocExampleContext implements Context
{
    protected $stdout = null;
    protected $stderr = null;
    protected $status = null;
    protected $outputDirResolver = null;

    protected function getOutputDirResolver(): OutputDirResolver
    {
        if (null === $this->outputDirResolver) {
            $this->outputDirResolver = new OutputDirResolver();
        }
        return $this->outputDirResolver;
    }

    protected function getOutputPath(string $file)
    {
        return $this->getOutputDirResolver()->path($file);
    }

    protected function getExamplePath(string $file)
    {
        return realpath(__DIR__.'/../examples/'.$file);
    }

    protected function getExistingOutputPath(string $file)
    {
        $path = $this->getOutputPath($file);
        if ($path === false) {
            throw new \RuntimeException("output file does not exist: $file");
        }
        return $path;
    }

    protected function getExistingExamplePath(string $file)
    {
        $path = $this->getExamplePath($file);
        if ($path === false) {
            throw new \RuntimeException("example file does not exist: $file");
        }
        return $path;
    }

    protected function getTopPath(string $file = '')
    {
        return realpath(__DIR__.'/../../..'.($file ? '/'.$file : ''));
    }

    protected function getOutputFileContents(string $file, $default = '')
    {
        $path = $this->getOutputPath($file);
        if ($path === false || !file_exists($path)) {
            return $default;
        }
        return file_get_contents($path);
    }

    protected function getExampleCmd(string $file)
    {
        $path = $this->getExistingExamplePath($file);
        return implode(" ", [PHP_BINARY, "-d auto_prepend_file=vendor/autoload.php", $path]);
    }

    protected function getPhpUnitCmd(string $file)
    {
        $tst = $this->getExistingExamplePath($file);
        $bin = $this->getTopPath('vendor/bin/phpunit');
        $xml = $this->getExamplePath('phpunit.xml');
        return implode(" ", [PHP_BINARY, $bin, '-c', $xml, '--colors=never', $tst]);
    }

    protected function runDocExample(string $file)
    {
        $cmd = $this->getExampleCmd($file);
        $this->runCommand($cmd);
    }

    protected function runPhpUnitExample(string $file)
    {
        $cmd = $this->getPhpUnitCmd($file);
        $this->runCommand($cmd);
    }

    protected function sanitizeOutput(string $string)
    {
        //$lines = explode(PHP_EOL, $string);
        $patterns = [
            '/\r/',
        ];
        $replaces = [
            '',
        ];
        return preg_replace($patterns, $replaces, $string);
    }

    protected function sanitizePhpUnitOutput(string $string)
    {
        $top = preg_quote($this->getTopPath(), '/');

        $patterns = [
            '/\r/',
            '/^(PHPUnit )(?:\$version|\d+(?:\.\w+)*)( by Sebastian Bergmann and contributors\.)$/m',
            '/^(Time: )(?:(?:\d+)(?::\d+)?(?:\.\d+)?(?: \w+)?)(, Memory: )(?:\d+(?:\.\d+)?(?: \d+)?( \w+)?)$/m',
            '/^(?:(?:phpvfscomposer:\/\/)?(?:'.$top.'\/))?((?:packages|vendor)(?:\/[\w-]+)+(?:\.php)?):\d+[\\r\\n]?/m',
            '/^(?:(?:phpvfscomposer:\/\/)?(?:'.$top.'\/))docs\/sphinx\/examples\/(?:[^\/]*\/)*([\w-]+\.php):(\d+)$/m',
            '/^(Configuration:\s*)((?:phpvfscomposer:\/\/)?(?:'.$top.'\/)?docs\/)?sphinx\/examples\/phpunit.xml$/m',
            '/^(Runtime:\s*)(PHP\s*)(?:\$phpversion|\d+(?:\.\w+)*)(\s*)$/m',
            '/(Tailors\\\\PHPUnit\\\\Values\\\\\\w+ Object)\\s+(?:(?:#\\d+)|(?:&[0-9a-fA-F]+))\\s*\\(/m',
        ];
        $replaces = [
            '',
            '\1$version\2',
            '\1$time\2$memory',
            '',
            '\1:\2',
            '\1phpunit.xml',
            '\1\2$phpversion\3',
            '\1 ('
        ];
        return preg_replace($patterns, $replaces, $string);
    }

    protected function runCommand(string $cmd)
    {
        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ];
        $cwd = realpath($this->getTopPath());

        $proc = proc_open($cmd, $descriptorspec, $pipes, $cwd);

        if (!is_resource($proc)) {
            throw new \RuntimeException("could not run command: $cmd");
        }

        fclose($pipes[0]);

        $this->stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        $this->stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $this->status = proc_close($proc);
    }

    protected function resetExample()
    {
        $this->stdout = null;
        $this->stderr = null;
        $this->status = null;
    }

    /**
     * @When I executed doc example :file
     */
    public function iExecutedDocExample(string $file)
    {
        $this->resetExample();
        $this->runDocExample($file);
    }

    /**
     * @When I tested :file with PHPUnit
     */
    public function iTestedWithPhpUnit(string $file)
    {
        $this->resetExample();
        $this->runPhpUnitExample($file);
    }

    /**
     * @Then I should see stdout from :file
     */
    public function iShouldSeeStdoutFrom(?string $file = null)
    {
        if (!is_null($file)) {
            Assert::assertSame(
                $this->getOutputFileContents($file),
                $this->sanitizeOutput($this->stdout)
            );
        }
    }

    /**
     * @Then I should see stderr from :file
     */
    public function iShouldSeeStderrFrom(?string $file = null)
    {
        if (!is_null($file)) {
            Assert::assertSame(
                $this->getOutputFileContents($file),
                $this->sanitizeOutput($this->stderr)
            );
        }
    }

    /**
     * @Then I should see exit code :code
     */
    public function iShouldSeeExitCode(int $code)
    {
        Assert::assertSame($code, $this->status);
    }

    /**
     * @Then I should see PHPunit stdout from :file
     */
    public function iShouldSeePhpUnitStdoutFrom(?string $file = null)
    {
        if (!is_null($file)) {
            $expect = $this->sanitizePhpUnitOutput($this->getOutputFileContents($file));
            $actual = $this->sanitizePhpUnitOutput($this->stdout);
            Assert::assertSame($expect, $actual);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
