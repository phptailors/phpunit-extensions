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
     * @psalm-param array-key         $key
     * @psalm-param ?non-empty-string $fallback
     *
     * @psalm-return non-empty-string
     */
    public static function get(mixed $key, ?string $fallback = null): string
    {
        /** @psalm-var array<mixed,non-empty-string> */
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
}

// vim: syntax=php sw=4 ts=4 et:
