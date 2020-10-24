<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 * @template-extends AbstractPropertySelector<class-string>
 */
final class ClassPropertySelector extends AbstractPropertySelector
{
    /**
     * @param mixed $subject
     * @psalm-assert-if-true class-string $subject
     */
    public function supports($subject): bool
    {
        return is_string($subject) && class_exists($subject);
    }

    /**
     * A short string naming the subject type supported by this selector.
     */
    public function subject(): string
    {
        return 'a class';
    }

    /**
     * @param mixed $subject
     * @param mixed $key
     *
     * @return mixed
     *
     * @psalm-param class-string $subject
     * @psalm-param array-key $key
     */
    protected function getSubjectAttribute($subject, $key)
    {
        return $subject::${$key};
    }
}

// vim: syntax=php sw=4 ts=4 et:
