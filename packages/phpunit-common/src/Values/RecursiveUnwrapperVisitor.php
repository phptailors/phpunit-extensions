<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\CircularDependencyException;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveUnwrapperVisitor implements RecursiveVisitorInterface
{
    public const UNIQUE_TAG = 'unwrapped-values:$1$zIlgusJc$ZZCyNRPOX1SbpKdzoD2hU/';

    /**
     * @var bool
     */
    private $tagging;

    /**
     * @var list<ValuesInterface>
     */
    private $objectStack;

    /**
     * @var array
     */
    private $result;

    public function __construct(bool $tagging = true)
    {
        $this->tagging = $tagging;
        $this->objectStack = [];
        $this->result = [];
    }

    /**
     * @psalm-mutation-free
     */
    public function result(): array
    {
        return $this->result;
    }

    /**
     * @param array|ValuesInterface $node
     * @param list<array-key>       $path
     */
    public function enter($node, array $path): bool
    {
        if ($node instanceof ValuesInterface) {
            if (($count = count($this->objectStack)) > 0) {
                $parent = $this->objectStack[$count - 1];
                $iterate = $parent->actual() === $node->actual();
            } else {
                $iterate = true;
            }

            $this->objectStack[] = $node;
        } else {
            $iterate = true;
        }

        if (!$iterate) {
            self::set($this->result, $path, $node);
        }

        return $iterate;
    }

    /**
     * @param array|ValuesInterface $node
     * @param list<array-key>       $path
     */
    public function leave($node, array $path): void
    {
        if ($node instanceof ValuesInterface) {
            array_pop($this->objectStack);

            if ($this->tagging) {
                // Distinguish unwrapped values from regular arrays
                // by adding UNIQUE TAG AT THE END of $array.
                $path[] = self::UNIQUE_TAG;
                self::set($this->result, $path, true);
            }
        }
    }

    /**
     * @param mixed           $node
     * @param list<array-key> $path
     */
    public function visit($node, array $path): void
    {
        self::set($this->result, $path, $node);
    }

    /**
     * @param mixed           $node
     * @param list<array-key> $path
     *
     * @return never
     *
     * @throws CircularDependencyException
     */
    public function cycle($node, array $path): bool
    {
        self::throwCircular($path);
    }

    /**
     * @param array           $array
     * @param list<array-key> $path
     * @param mixed           $value
     *
     * @psalm-suppress UnusedParam
     * @psalm-suppress UnusedVariable
     */
    private static function set(array &$array, array $path, $value): void
    {
        $current = &$array;
        foreach ($path as $key) {
            if (!is_array($current)) {
                return;
            }
            if (!array_key_exists($key, $current)) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }

        /**
         * @psalm-var mixed
         */
        $current = $value;
    }

    /**
     * @param list<array-key> $path
     *
     * @return never
     *
     * @throws CircularDependencyException
     */
    private static function throwCircular(array $path): void
    {
        $pathString = self::pathString($path);

        throw new CircularDependencyException("Circular dependency found in nested values at \$values{$pathString}.");
    }

    /**
     * @param list<array-key> $path
     *
     * @psalm-mutation-free
     */
    private static function pathString(array $path): string
    {
        return implode('', array_map(function ($key) {
            return '['.(is_string($key) ? ('"'.addslashes($key).'"') : (string) $key).']';
        }, $path));
    }
}

// vim: syntax=php sw=4 ts=4 et:
