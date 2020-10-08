<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 * @extends AbstractValueSelector<object|class-string>
 * @template SubjectType
 */
abstract class AbstractPropertySelector extends AbstractValueSelector
{
    /**
     * A name for the values being selected from subject (in plural,
     * e.g. "values" or "properties").
     */
    public function selectable(): string
    {
        return 'properties';
    }

    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $retval
     *
     * @psalm-param object|class-string $subject
     * @psalm-param array-key $key
     */
    final protected function selectFromSupported($subject, $key, &$retval = null): bool
    {
        $method = ('()' === substr((string) $key, -2)) ? substr((string) $key, 0, -2) : null;
        if (null !== $method) {
            return $this->selectWithMethod($subject, $method, $retval);
        }

        return $this->selectWithAttribute($subject, $key, $retval);
    }

    /**
     * @param mixed $subject
     * @param mixed $key
     *
     * @return mixed
     *
     * @psalm-param SubjectType $subject
     * @psalm-param array-key $key
     */
    abstract protected function getSubjectAttribute($subject, $key);

    /**
     * @param mixed $subject
     * @param mixed $retval
     *
     * @psalm-param object|class-string $subject
     * @param-out mixed $retval
     */
    final protected function selectWithMethod($subject, string $method, &$retval = null): bool
    {
        if (!method_exists($subject, $method)) {
            return false;
        }
        /** @psalm-var mixed */
        $retval = call_user_func([$subject, $method]);

        return true;
    }

    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $retval
     *
     * @param-out mixed $retval
     * @psalm-param object|class-string $subject
     * @psalm-param array-key $key
     * @psalm-assert class-string $subject
     */
    final protected function selectWithAttribute($subject, $key, &$retval = null): bool
    {
        $key = (string) $key;
        if (!property_exists($subject, $key)) {
            return false;
        }
        /** @psalm-var mixed */
        $retval = $this->getSubjectAttribute($subject, $key);

        return true;
    }
}

// vim: syntax=php sw=4 ts=4 et:
