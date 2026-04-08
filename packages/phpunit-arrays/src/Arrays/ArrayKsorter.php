<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-extends AbstractSorter<\Traversable|array>
 */
final class ArrayKsorter extends AbstractSorter
{
    private int $flags;

    public function __construct(int $flags)
    {
        $this->flags = $flags;
    }

    /**
     * @param mixed $subject
     *
     * @psalm-assert-if-true \Traversable|array $subject
     */
    public function supports($subject): bool
    {
        return is_array($subject) || $subject instanceof \Traversable;
    }

    /**
     * Returns short string explaining the type(s) of subjects the selector
     * supports.
     */
    public function subject(): string
    {
        return 'an array or Traversable';
    }

    /**
     * A name for the values being selected from subject.
     */
    public function sortable(): string
    {
        return 'keys';
    }

    /**
     * @param mixed $subject
     *
     * @psalm-param \Traversable|array $subject
     *
     * @return array
     */
    protected function sortSupported($subject): array
    {
        if ($subject instanceof \Traversable) {
            $array = \iterator_to_array($subject, true);
        } else {
            $array = $subject;
        }

        ksort($array, $this->flags);

        return $array;
    }
}

// vim: syntax=php sw=4 ts=4 et:
