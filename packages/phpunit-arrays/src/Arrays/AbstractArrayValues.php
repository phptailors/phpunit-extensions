<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Values\AbstractValues;
use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * An array of expected or actual array values.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
abstract class AbstractArrayValues extends AbstractValues
{
    /**
     * @param array|\Traversable $array
     */
    final public function __construct($array = [])
    {
        parent::__construct($array);
    }

    /**
     * @psalm-return non-empty-string
     */
    final public function familyName(): string
    {
        return __NAMESPACE__.'\ArrayValues';
    }

    /**
     * @psalm-return non-empty-string
     */
    final public function tag(): string
    {
        return $this->familyTag();
    }

    /**
     * @param array|\Traversable $array
     */
    final public function createActualValues($array = []): ValuesInterface
    {
        return new ActualArrayValues($array);
    }

    final protected function fallbackFamilyString(): string
    {
        return 'c225435bd5434279f77fb3cddf138302a5c826ec';
    }
}

// vim: syntax=php sw=4 ts=4 et:
