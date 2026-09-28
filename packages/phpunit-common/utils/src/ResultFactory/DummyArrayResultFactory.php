<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ResultFactory;

use Tailors\PHPUnit\ArrayResult\DummyArrayResult;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
final class DummyArrayResultFactory extends AbstractArrayResultFactory
{
    /**
     * @var ?string
     *
     * @psalm-var ?non-falsy-string
     *
     * @psalm-readonly
     */
    private $tag;

    /**
     * @var \Closure
     *
     * @psalm-var \Closure(ArrayLike): array
     *
     * @psalm-readonly
     */
    private $modifier;

    public function __construct(?string $tag = null, ?\Closure $modifier = null)
    {
        $this->tag = $tag;
        $this->modifier = $modifier;
    }

    /**
     * @psalm-param ArrayLike $input
     */
    protected function getArrayResult(bool $actual, iterable $input): ResultInterface
    {
        if (null !== $this->modifier) {
            $input = call_user_func_array($this->modifier, [$input]);
        }

        return new DummyArrayResult($actual, $input, $this->tag);
    }
}

// vim: syntax=php sw=4 ts=4 et:
