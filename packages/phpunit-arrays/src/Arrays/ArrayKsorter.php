<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ArrayKsorter implements SorterInterface
{
    private int $flags;

    public function __construct(int $flags)
    {
        $this->flags = $flags;
    }

    /**
     * @param mixed $subject
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public function sorted($subject): array
    {
        $this->assertSupports($subject, 1);

        return $this->sortSupported($subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-assert-if-true array $subject
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
     * @psalm-param array $subject
     *
     * @return array
     *
     * @throws InvalidArgumentException
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

    /**
     * @psalm-assert SubjectType $subject
     *
     * @param mixed $subject
     * @param int   $argument
     * @param int   $distance
     *
     * @throws InvalidArgumentException
     */
    final protected function assertSupports($subject, int $argument, int $distance = 1): void
    {
        if (!$this->supports($subject)) {
            $provided = is_object($subject) ? 'an object '.get_class($subject) : gettype($subject);

            throw InvalidArgumentException::fromBackTrace($argument, $this->subject(), $provided, 1 + $distance);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
