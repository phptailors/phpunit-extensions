<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ResultFactory;

use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type SupportedInput = iterable<array-key, mixed>
 *
 * @template-implements ResultFactoryInterface<ArrayLike>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
abstract class AbstractArrayResultFactory implements ResultFactoryInterface
{
    /**
     * @psalm-assert-if-true SupportedInput $input
     */
    final public function supports(mixed $input): bool
    {
        return is_iterable($input);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-assert SupportedInput $input
     *
     * @psalm-return ResultInterface&\Traversable
     */
    final public function getResult(bool $actual, mixed $input): ResultInterface
    {
        self::assertSupports($input, 2);

        return $this->getArrayResult($actual, $input);
    }

    /**
     * @psalm-param ArrayLike $input
     *
     * @psalm-return ResultInterface&\Traversable
     */
    abstract protected function getArrayResult(bool $actual, iterable $input): ResultInterface;

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-assert SupportedInput $input
     */
    final protected function assertSupports(mixed $input, int $argument, int $distance = 1): void
    {
        if ($this->supports($input)) {
            return;
        }

        $expected = 'an array or Traversable';
        $provided = get_debug_type($input);

        throw InvalidArgumentException::fromBackTrace($argument, $expected, $provided, $distance + 1);
    }
}

// vim: syntax=php sw=4 ts=4 et:
