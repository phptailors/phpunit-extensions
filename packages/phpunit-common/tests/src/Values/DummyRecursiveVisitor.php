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
     * @param bool|\Closure(mixed,list<array-key>):bool $visit
     * @param bool|\Closure(mixed,list<array-key>):bool $cycle
     */
    public function __construct(private bool|\Closure $visit = true, private bool|\Closure $cycle = false)
    {
        $this->trace = [];
    }

    public function visit(mixed $node, array $path): bool
    {
        $this->trace[] = ['func' => 'visit', 'node' => &$node, 'path' => $path];

        if (is_bool($this->visit)) {
            return $this->visit;
        }

        return call_user_func_array($this->visit, [&$node, $path]);
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
