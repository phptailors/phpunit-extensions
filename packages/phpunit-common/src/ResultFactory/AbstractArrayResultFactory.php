<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ResultFactory;

use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Result\ResultInterface;


/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 * @psalm-type SupportedInput = iterable<array-key, mixed>
 *
 * @template-implements ResultFactoryInterface<iterable<array-key,mixed>>
 */
abstract class AbstractArrayResultFactory implements ResultFactoryInterface
{
    /**
     * @psalm-param ArrayLike $input
     */
    abstract protected function getArrayResult(bool $actual, iterable $input): ResultInterface;

    /**
     * @param mixed $input
     *
     * @psalm-assert-if-true SupportedInput $input
     */
    final public function supports($input): bool
    {
        return is_iterable($input);
    }

    /**
     * @param mixed $input
     *
     * @throws InvalidArgumentException
     *
     * @psalm-assert SupportedInput $input
     */
    final public function getResult(bool $actual, $input): ResultInterface
    {
        $this->assertSupports($input, 2);

        return $this->getArrayResult($actual, $input);
    }

    /**
     * @param mixed $input
     *
     * @throws InvalidArgumentException
     *
     * @psalm-assert SupportedInput $input
     */
    final protected function assertSupports($input, int $argument, int $distance = 1): void
    {
        if ($this->supports($input)) {
            return;
        }

        $expected = 'an array or Traversable';
        $provided = is_object($input) ? get_class($input) : gettype($input);

        throw InvalidArgumentException::fromBackTrace($argument, $expected, $provided, $distance + 1);
    }
}

// vim: syntax=php sw=4 ts=4 et:
