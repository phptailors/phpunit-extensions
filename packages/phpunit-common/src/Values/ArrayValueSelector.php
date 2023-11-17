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
 * @template-extends AbstractValueSelector<array|\ArrayAccess>
 */
final class ArrayValueSelector extends AbstractValueSelector
{
    /**
     * @param mixed $subject
     *
     * @psalm-assert-if-true array|\ArrayAccess $subject
     */
    public function supports($subject): bool
    {
        return is_array($subject) || $subject instanceof \ArrayAccess;
    }

    /**
     * Returns short string explaining the type(s) of subjects the selector
     * supports.
     */
    public function subject(): string
    {
        return 'an array or ArrayAccess';
    }

    /**
     * A name for the values being selected from subject.
     */
    public function selectable(): string
    {
        return 'values';
    }

    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $retval
     *
     * @psalm-param array|\ArrayAccess $subject
     * @psalm-param array-key $key
     *
     * @param-out mixed $retval
     *
     * @throws InvalidArgumentException
     */
    protected function selectFromSupported($subject, $key, &$retval = null): bool
    {
        if (self::subjectHasKey($subject, $key)) {
            /** @psalm-var mixed */
            $retval = $subject[$key];

            return true;
        }

        return false;
    }

    /**
     * @param mixed $subject
     * @param mixed $key
     *
     * @psalm-param array|\ArrayAccess $subject
     * @psalm-param array-key $key
     */
    private static function subjectHasKey($subject, $key): bool
    {
        if ($subject instanceof \ArrayAccess) {
            return $subject->offsetExists($key);
        }

        return array_key_exists($key, $subject);
    }
}

// vim: syntax=php sw=4 ts=4 et:
