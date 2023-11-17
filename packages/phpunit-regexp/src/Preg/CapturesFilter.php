<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Preg;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class CapturesFilter implements CapturesFilterInterface
{
    /**
     * @var int
     *
     * @psalm-readonly
     */
    private $flags;

    /**
     * @param int $flags PREG_* flags such as PREG_UNMATCHED_AS_NULL or PREG_OFFSET_CAPTURE
     */
    public function __construct(int $flags = 0)
    {
        $this->flags = $flags;
    }

    /**
     * Filter-out all elements of $array with $this->accepts().
     *
     * @return array the filtered array
     *
     * @psalm-return array<array-key, string|null|array{0:string|null,1:int}>
     */
    public function filter(array $array): array
    {
        return array_filter($array, [$this, 'accepts']);
    }

    /**
     * Returns true if $value may be a capture group returned by preg_match().
     *
     * The method shall return true in following situations:
     *
     *  - $value is a string or PREG_UNMATCHED_AS_NULL is set and $value is null,
     *  - PREG_OFFSET_CAPTURE is set and
     *
     *      - $value is two-element array, and
     *      - $value[0] is a string or PREG_UNMATCHED_AS_NULL is set and $value is null, and
     *      - $value[1] is an integer,
     *
     * @param mixed $value
     *
     * @psalm-assert-if-true string|null|array{0:string|null,1:int} $value
     */
    public function accepts($value): bool
    {
        return $this->isScalarCapture($value) || $this->isArrayCapture($value);
    }

    /**
     * @param mixed $value
     *
     * @psalm-assert-if-true string|null $value
     */
    private function isScalarCapture($value): bool
    {
        return is_string($value) || (null === $value && (0 !== ($this->flags & PREG_UNMATCHED_AS_NULL)));
    }

    /**
     * @param mixed $value
     *
     * @psalm-assert-if-true array{0:string|null,1:int} $value
     */
    private function isArrayCapture($value): bool
    {
        if (!is_array($value) || (0 === ($this->flags & PREG_OFFSET_CAPTURE))) {
            return false;
        }

        return (2 === count($value)) && $this->isScalarCapture($value[0]) && is_int($value[1]);
    }
}

// vim: syntax=php sw=4 ts=4 et:
