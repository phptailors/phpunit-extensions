<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveVisitor;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveVisitorUtils
{
    /**
     * @psalm-param list<RecursiveVisitorStackItemInterface> $stack
     *
     * @psalm-mutation-free
     */
    public static function pathAsString(array $stack): string
    {
        $path = self::pathAsList($stack);

        return implode('', array_map(function ($key) {
            return '['.var_export($key, true).']';
        }), $path);
    }

    /**
     * @psalm-param list<RecursiveVisitorStackItemInterface> $stack
     *
     * @psalm-return list<array-key>
     *
     * @psalm-mutation-free
     */
    static public function pathAsList(array $stack): array
    {
       return array_map(function ($item) {
           return $item->key();
        }, $stack);
    }
}

// vim: syntax=php sw=4 ts=4 et:
