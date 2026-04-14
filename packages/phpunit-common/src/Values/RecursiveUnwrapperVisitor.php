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
     * @var list<ValuesInterface>
     */
    private array $objectStack;

    /**
     * @var array
     */
    private array $result;

    public function __construct(private bool $tagging = true)
    {
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
     * @param list<array-key> $path
     */
    public function enter(array|ValuesInterface $node, array $path): bool
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
     * @param list<array-key> $path
     */
    public function leave(array|ValuesInterface $node, array $path): void
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
     * @param list<array-key> $path
     */
    public function visit(mixed $node, array $path): void
    {
        self::set($this->result, $path, $node);
    }

    /**
     * @param list<array-key> $path
     *
     * @return never
     *
     * @throws CircularDependencyException
     */
    public function cycle(mixed $node, array $path): bool
    {
        self::throwCircular($path);
    }

    /**
     * @param array           $array
     * @param list<array-key> $path
     *
     * @psalm-suppress UnusedParam
     * @psalm-suppress UnusedVariable
     */
    private static function set(array &$array, array $path, mixed $value): void
    {
        if (0 === count($path)) {
            if (is_array($value)) {
                $array = $value;
            }

            return;
        }

        $current = &$array;

        $top = array_pop($path);
        foreach ($path as $key) {
            if (!array_key_exists($key, $current)) {
                $current[$key] = [];
            }

            if (!is_array($current[$key])) {
                return;
            }

            $current = &$current[$key];
        }

        /** @psalm-var mixed */
        $current[$top] = $value;
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
