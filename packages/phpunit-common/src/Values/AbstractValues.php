<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\Common\StaticRandomStrings;

/**
 * An array of expected values.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-extends \ArrayObject<array-key,mixed>
 */
abstract class AbstractValues extends \ArrayObject implements ValuesInterface
{
    protected function __construct(array|\Traversable $array = [])
    {
        if (!is_array($array)) {
            $array = iterator_to_array($array);
        }

        parent::__construct($array);
    }

    /**
     * @psalm-return non-empty-string
     */
    final protected function familyTag(): string
    {
        $family = $this->familyName();

        $random = StaticRandomStrings::get($family, $this->fallbackFamilyString());

        return "{$family}:{$random}";
    }

    /**
     * @psalm-return non-empty-string
     */
    abstract protected function familyName(): string;

    /**
     * @psalm-return non-empty-string
     */
    abstract protected function fallbackFamilyString(): string;
}

// vim: syntax=php sw=4 ts=4 et:
