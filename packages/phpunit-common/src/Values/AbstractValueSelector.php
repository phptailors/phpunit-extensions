<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-template SubjectType
 */
abstract class AbstractValueSelector implements ValueSelectorInterface
{
    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $retval
     *
     * @throws InvalidArgumentException
     *
     * @psalm-param array-key $key
     */
    final public function select($subject, $key, &$retval = null): bool
    {
        $this->assertSupports($subject, 1);

        return $this->selectFromSupported($subject, $key, $retval);
    }

    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $retval
     *
     * @psalm-param SubjectType $subject
     * @psalm-param array-key $key
     */
    abstract protected function selectFromSupported($subject, $key, &$retval = null): bool;

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
