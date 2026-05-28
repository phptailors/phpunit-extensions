<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

/**
 * An array of actual or expected values.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class StaticRandomStrings
{
    public const STRINGLEN = 40;

    /**
     * @param mixed $key
     *
     * @psalm-param array-key         $key
     * @psalm-param ?non-empty-string $fallback
     *
     * @psalm-return non-empty-string
     */
    public static function get($key, ?string $fallback = null): string
    {
        /** @psalm-var array<mixed,non-falsy-string> */
        static $strings = [];

        if (null === ($strings[$key] ?? null)) {
            try {
                $hex = bin2hex(random_bytes(self::STRINGLEN / 2));
                // @codeCoverageIgnoreStart
            } catch (\Exception $e) {
                if (null === $fallback) {
                    /** @psalm-suppress MissingThrowsDocblock */
                    throw $e;
                }
                $hex = $fallback;
            }
            // @codeCoverageIgnoreEnd
            $strings[$key] = $hex;
        }

        return $strings[$key];
    }

    /**
     * @param object|class-string $objectOrClass
     *
     * @psalm-param ?non-empty-string $fallback
     *
     * @psalm-return non-falsy-string
     */
    public static function classTag($objectOrClass, ?string $fallback = null): string
    {
        $class = is_object($objectOrClass) ? get_class($objectOrClass) : $objectOrClass;

        if (null === $fallback) {
            /** @psalm-var non-empty-string */
            $fallback = sha1($class);
        }

        return "{$class}:".self::get($class, $fallback);
    }

    /**
     * @psalm-param ?non-empty-string $fallback
     *
     * @psalm-return non-falsy-string
     */
    public static function familyTag(string $family, ?string $fallback = null): string
    {
        return "{$family}:".self::get($family, $fallback);
    }
}

// vim: syntax=php sw=4 ts=4 et:
