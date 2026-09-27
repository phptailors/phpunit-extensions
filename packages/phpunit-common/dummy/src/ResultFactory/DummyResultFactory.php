<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ResultFactory;

use Tailors\PHPUnit\Result\DummyResult;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-implements ResultFactoryInterface<mixed>
 */
final class DummyResultFactory implements ResultFactoryInterface
{
    /**
     * @var bool|\Closure(mixed):bool
     */
    private $supports;

    /**
     * @param bool|\Closure(mixed):bool $supports
     */
    public function __construct($supports)
    {
        $this->supports = $supports;
    }

    /**
     * @param mixed $input
     */
    public function supports($input): bool
    {
        if (is_callable($this->supports)) {
            return call_user_func($this->supports, $input);
        }

        return $this->supports;
    }

    /**
     * @param mixed $input
     *
     * @psalm-assert SupportedInput $input
     */
    public function getResult(bool $actual, $input): ResultInterface
    {
        return new DummyResult($actual);
    }
}

// vim: syntax=php sw=4 ts=4 et:
