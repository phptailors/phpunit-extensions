<?php declare(strict_types=1);

namespace Tailors\PHPUnit\Docs\Behat;

final class Path
{
    public static function relative(string $path, string $base, string $cwd = null): string
    {
        $pathAbsolute = self::isAbsolute($path);
        $baseAbsolute = self::isAbsolute($base);

        if (!$pathAbsolute || !$baseAbsolute) {
            if (null === $cwd) {
                $cwd = getcwd();
            }

            if (!$pathAbsolute) {
                $path = $cwd.'/'.$path;
            }

            if (!$baseAbsolute) {
                $base = $cwd.'/'.$base;
            }
        }

        $path = self::canonical($path);
        $base = self::canonical($base);

        $pathSegments = explode('/', $path);
        $baseSegments = explode('/', $base);

        // Remove common segments at the beginning of the paths
        while (count($pathSegments) > 0 && count($baseSegments) > 0 && $pathSegments[0] === $baseSegments[0]) {
            array_shift($pathSegments);
            array_shift($baseSegments);
        }

        $resultSegments = array_merge(array_fill(0, count($baseSegments), '..'), $pathSegments);

        return implode('/', $resultSegments);
    }

    public static function canonical(string $path): string
    {
        $path = preg_replace(['/\\/+/', '/(.)\\/$/'], ['/', '\\1'], $path);
        $segments = explode('/', $path);

        if (true === ($absolute = (($segments[0] ?? null) === ''))) {
            array_shift($segments);
            $backtrace = [""];
        } else {
            $backtrace = [];
        }

        $stack = [];
        foreach ($segments as $segment) {
            $backtrace[] = $segment;
            if ($segment === '.') {
                continue;
            }

            if ($segment === '..') {
                if (count($stack) === 0) {
                    if ($absolute) {
                        $message = sprintf(
                            'Invalid path %s, too many levels of ".." at %s',
                            var_export($path, true),
                            var_export(implode('/', $backtrace), true)
                        );
                        throw new \InvalidArgumentException($message);
                    }
                    $stack[] = '..';
                } else {
                    if ('..' == array_pop($stack)) {
                        array_push($stack, '..', '..');
                    }
                }
            } else {
                $stack[] = $segment;
            }
        }

        if ($absolute) {
            array_unshift($stack, '');
        }

        if ('' === ($canonical = implode('/', $stack))) {
            $canonical = '.';
        }

        return $canonical;
    }

    public static function isAbsolute(string $path): bool
    {
        return strpos($path, '/') === 0;
    }
}
