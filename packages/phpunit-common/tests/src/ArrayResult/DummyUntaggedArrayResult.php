<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

use Tailors\PHPUnit\Result\ResultInterface;


/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 *
 * @template-extends \ArrayObject<mixed,mixed>
 */
final class DummyUntaggedArrayResult extends \ArrayObject implements ResultInterface
{
    /**
     * @var bool
     *
     * @psalm-readonly
     */
    private $actual;

    /**
     * @psalm-param ArrayLike $array
     */
    public function __construct(bool $actual, iterable $array = [])
    {
        $this->actual = $actual;

        if (!is_array($array)) {
            $array = iterator_to_array($array);
        }

        parent::__construct($array);
    }

    /**
     * @psalm-mutation-free
     */
    public function actual(): bool
    {
        return $this->actual;
    }
}

// vim: syntax=php sw=4 ts=4 et:
