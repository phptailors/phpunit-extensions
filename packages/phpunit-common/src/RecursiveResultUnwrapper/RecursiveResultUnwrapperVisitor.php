<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultUnwrapper;

use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\StaticTagInterface;
use Tailors\PHPUnit\Common\TagInterface;
use Tailors\PHPUnit\InternalErrorException;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorStackItemInterface;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorInterface;
use Tailors\PHPUnit\RecursiveVisitor\RecursiveVisitorUtils;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-implements RecursiveVisitorInterface<RecursiveResultUnwrapperStackItem>
 *
 * @psalm-type StackItem = RecursiveResultUnwrapperStackItem
 */
final class RecursiveResultUnwrapperVisitor implements RecursiveResultUnwrapperVisitorInterface, StaticTagInterface
{
    /**
     * @var bool
     *
     * @psalm-readonly
     */
    private $actual;

    /**
     * @var bool
     *
     * @psalm-readonly
     */
    private $tagging;

    /**
     * @var array|null|ResultInterface
     */
    private $result;

    /**
     * @var array
     */
    private $current;

    public function __construct(bool $actual, bool $tagging = true)
    {
        $this->actual = $actual;
        $this->tagging = $tagging;
        $this->reset();
    }

    /**
     * Returns random string generated once per process run.
     *
     * @psalm-return non-empty-string
     */
    public static function tag(): string
    {
        $hex = StaticRandomStrings::get(self::class, '4694a81d074f3386a9b8c7c2ad04914e120f1a10');

        return __NAMESPACE__."\UnwrappedResult:{$hex}";
    }

    public function reset(): void
    {
        $this->result = null;
        $this->current = [];
    }

    /**
     * @return array|null|ResultInterface
     *
     * @psalm-mutation-free
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * @param array|\Traversable $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function enter($node, array $stack): bool
    {
        if ($node instanceof ResultInterface) {
            $iterate = $node->actual() === $this->actual;
        } else {
            $iterate = is_array($node);
        }

        if ($iterate) {
            $this->current = [];
        }

        return $iterate;
    }

    /**
     * @param array|\Traversable $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function leave($node, array $stack, bool $iterating): void
    {
        if (!$iterating) {
            return;
        }

        if ($node instanceof \Traversable && $this->tagging) {
            $tag = self::tag();
            if (array_key_exists($tag, $this->current)) {
                $path = (RecursiveVisitorUtils::pathAsString($stack))."[{$tag}]";
                /** @psalm-suppress MissingThrowsDocblock */
                throw InternalErrorException::fromBackTrace(
                    'Failed to set $array'.$path.': key already exists. Please re-run your tests.'
                );
            }

            // Distinguish unwrapped values from regular arrays
            // by adding object's tag at the end of $array.
            $this->current[$tag] = $this->getNodeTag($node);
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
     * @param array|\Traversable $node
     *
     * @psalm-param list<StackItem> $stack
     */
    public function cycle($node, array $stack): bool
    {
        $this->set($stack, $node);

        return false;
    }

    /**
     * @param array|\Traversable $node
     * @param mixed                 $key
     *
     * @psalm-param array-key       $key
     * @psalm-param list<StackItem> $stack
     *
     * @psalm-return StackItem
     */
    public function makeStackItem($node, $key, array $stack): RecursiveVisitorStackItemInterface
    {
        return new RecursiveResultUnwrapperStackItem($node, $key, $this->current);
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
            if (!is_array($value) && ! $value instanceof ResultInterface) {
                $expected = 'an array or '.ResultInterface::class.' object';
                $actual = is_object($value) ? get_class($value) : gettype($value);

                /** @psalm-suppress MissingThrowsDocblock */
                throw InvalidArgumentException::fromBackTrace(2, $expected, $actual);
            }
            $this->result = $value;

            return;
        }

        $stack[$count - 1]->set($value);
    }

    /**
     * @psalm-return non-empty-string
     */
    private function getNodeTag(object $node): string
    {
        if ($node instanceof TagInterface) {
            return $node->tag();
        }

        return StaticRandomStrings::classTag($node);
    }
}

// vim: syntax=php sw=4 ts=4 et:
