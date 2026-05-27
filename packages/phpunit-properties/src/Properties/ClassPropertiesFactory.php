<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\ResultFactory\AbstractArrayResultFactory;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ClassPropertiesFactory extends AbstractArrayResultFactory
{
    /**
     * @param array|\Traversable $input
     */
    protected function getArrayResult(bool $actual, $input): ResultInterface
    {
        return $actual ? new ActualClassProperties($input) : new ExpectedClassProperties($input);
    }
}

// vim: syntax=php sw=4 ts=4 et:
