<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\ResultFactory\AbstractArrayWrappingFactory;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * An array of expected class properties.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ObjectPropertiesFactory extends AbstractArrayWrappingFactory
{
    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param mixed $input
     *
     * @psalm-assert SupportedInput $input
     */
    public function getExpectedResult($input): ResultInterface
    {
        self::assertSupports($input);

        return new ExpectedObjectProperties($input);
    }

    /**
     * @throws InvalidArgumentException
     *
     * @psalm-param mixed $input
     *
     * @psalm-assert SupportedInput $input
     */
    public function getActualResult($input): ResultInterface
    {
        self::assertSupports($input);

        return new ActualObjectProperties($input);
    }
}

// vim: syntax=php sw=4 ts=4 et:
