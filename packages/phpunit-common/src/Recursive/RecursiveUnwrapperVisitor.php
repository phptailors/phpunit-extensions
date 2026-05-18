<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Recursive;

use Tailors\PHPUnit\CircularDependencyException;
use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\StaticTagInterface;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-implements RecursiveVisitorInterface<RecursiveUnwrapperStackItem>
 *
 * @psalm-type StackItem = RecursiveUnwrapperStackItem
 */
final class RecursiveUnwrapperVisitor implements RecursiveVisitorInterface, StaticTagInterface
{
    /**
     * @var bool
     */
    private $tagging;

    /**
     * @var array
     */
    private $result;

    /**
     * @var array
     */
    private $current;

    public function __construct(bool $tagging = true)
    {
        $this->tagging = $tagging;
        $this->result = [];
        $this->current = [];
    }

    /**
     * Returns random string generated once per process run.
     *
     * @psalm-return non-empty-string
     */
    public static function tag(): string
    {
        $hex = StaticRandomStrings::get(self::class, '4694a81d074f3386a9b8c7c2ad04914e120f1a10');

        return __NAMESPACE__."\UnwrappedValues:{$hex}";
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
     *
     * @psalm-param list<StackItem> $stack
     */
    public function enter($node, array $stack): bool
    {
        if ($node instanceof ValuesInterface) {
            $root = $node;
            foreach ($stack as $item) {
                $inode = $item->node();
                if ($inode instanceof ValuesInterface) {
                    $root = $inode;

                    break;
                }
            }
            $iterate = $root->actual() === $node->actual();
        } else {
            $iterate = true;
        }

        if ($iterate) {
            $this->current = [];
        }

        return $iterate;
    }

    /**
     * @param array|ValuesInterface $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function leave($node, array $stack, bool $iterating): void
    {
        if (!$iterating) {
            return;
        }

        if ($node instanceof ValuesInterface && $this->tagging) {
            // Distinguish unwrapped values from regular arrays
            // by adding UNIQUE TAG AT THE END of $array.
            $this->current[self::tag()] = $node->tag();
        }

        $this->set($stack, $this->current);
    }

    /**
     * @param mixed $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function visit($node, array $stack, bool $iterating): void
    {
        $this->set($stack, $node);
    }

    /**
     * @param array|ValuesInterface $node
     *
     * @return never
     *
     * @throws CircularDependencyException
     *
     * @psalm-param list<StackItem> $stack
     */
    public function cycle($node, array $stack): bool
    {
        self::throwCircular($stack);
    }

    /**
     * @param array|ValuesInterface $node
     * @param mixed                 $key
     *
     * @psalm-param array-key       $key
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem($node, $key, array $stack): RecursiveVisitorStackItemInterface
    {
        return new RecursiveUnwrapperStackItem($node, $key, $this->current);
    }

    /**
     * @psalm-param StackItem       $item
     * @psalm-param list<StackItem> $stack
     */
    public function freeStackItem(RecursiveVisitorStackItemInterface $item, array $stack): void
    {
        $this->current = $item->result();
    }

    /**
     * @param mixed $value
     *
     * @psalm-param list<StackItem> $stack
     */
    private function set(array $stack, $value): void
    {
        $count = count($stack);

        if (0 === $count) {
            if (!is_array($value)) {
                $actual = is_object($value) ? get_class($value) : gettype($value);

                /** @psalm-suppress MissingThrowsDocblock */
                throw InvalidArgumentException::fromBackTrace(2, 'an array', $actual);
            }
            $this->result = $value;

            return;
        }

        $last = $count - 1;
        $top = $stack[$last];
        $top->set($value);
    }

    /**
     * @return never
     *
     * @throws CircularDependencyException
     *
     * @psalm-param list<StackItem> $stack
     */
    private static function throwCircular(array $stack): void
    {
        $pathString = self::pathString($stack);

        throw new CircularDependencyException("Circular dependency found in nested values at \$values{$pathString}.");
    }

    /**
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-mutation-free
     */
    private static function pathString(array $stack): string
    {
        return implode('', array_map(function ($item) {
            return '['.var_export($item->key(), true).']';
        }, $stack));
    }
}

// vim: syntax=php sw=4 ts=4 et:
