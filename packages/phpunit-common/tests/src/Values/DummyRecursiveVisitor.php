<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class DummyRecursiveVisitor implements RecursiveVisitorInterface
{
    /**
     * @var list<array{func: string, node:mixed, path:list<array-key>}>
     */
    private array $trace;

    /**
     * @param bool|\Closure(array|ValuesInterface,list<array-key>):bool $enter
     * @param bool|\Closure(array|ValuesInterface,list<array-key>):bool $cycle
     */
    public function __construct(private bool|\Closure $enter = true, private bool|\Closure $cycle = false)
    {
        $this->trace = [];
    }

    /**
     * @param list<array-key> $path
     */
    public function enter(array|ValuesInterface $node, array $path): bool
    {
        $this->trace[] = ['func' => 'enter', 'node' => &$node, 'path' => $path];

        if (is_bool($this->enter)) {
            return $this->enter;
        }

        return call_user_func_array($this->enter, [&$node, $path]);
    }

    /**
     * @param list<array-key> $path
     */
    public function leave(array|ValuesInterface $node, array $path): void
    {
        $this->trace[] = ['func' => 'leave', 'node' => &$node, 'path' => $path];
    }

    public function visit(mixed $node, array $path): void
    {
        $this->trace[] = ['func' => 'visit', 'node' => &$node, 'path' => $path];
    }

    public function cycle(mixed $node, array $path): bool
    {
        $this->trace[] = ['func' => 'cycle', 'node' => &$node, 'path' => $path];

        if (is_bool($this->cycle)) {
            return $this->cycle;
        }

        return call_user_func_array($this->cycle, [&$node, $path]);
    }

    /**
     * @return list<array{func: string, node:mixed, path:list<array-key>}>
     *
     * @psalm-mutation-free
     */
    public function trace(): array
    {
        return $this->trace;
    }
}
// vim: syntax=php sw=4 ts=4 et:
