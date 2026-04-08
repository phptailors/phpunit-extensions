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
 *
 * @psalm-template SubjectType
 */
abstract class AbstractSorter implements SorterInterface
{
    /**
     * @param mixed $subject
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    final public function sorted($subject): array
    {
        $this->assertSupports($subject, 1);

        return $this->sortSupported($subject);
    }

    /**
     * @param mixed $subject
     *
     * @psalm-param SubjectType $subject
     *
     * @return array
     */
    abstract protected function sortSupported($subject): array;

    /**
     * @psalm-assert SubjectType $subject
     *
     * @param mixed $subject
     * @param int   $argument
     * @param int   $distance
     *
     * @throws InvalidArgumentException
     */
    private function assertSupports($subject, int $argument, int $distance = 1): void
    {
        if (!$this->supports($subject)) {
            $provided = is_object($subject) ? 'an object '.get_class($subject) : gettype($subject);

            throw InvalidArgumentException::fromBackTrace($argument, $this->subject(), $provided, 1 + $distance);
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
