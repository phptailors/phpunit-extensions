<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\Result\ResultInterface;
use Tailors\PHPUnit\ResultFactory\AbstractArrayResultFactory;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key,mixed>
 */
final class ObjectPropertiesFactory extends AbstractArrayResultFactory
{
    /**
     * @psalm-param ArrayLike $input
     */
    protected function getArrayResult(bool $actual, iterable $input): ResultInterface
    {
        return $actual ? new ActualObjectProperties($input) : new ExpectedObjectProperties($input);
    }
}

// vim: syntax=php sw=4 ts=4 et:
