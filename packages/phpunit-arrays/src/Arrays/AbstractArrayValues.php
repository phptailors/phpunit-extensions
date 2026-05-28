<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\ArrayResult\AbstractArrayResult;
use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\TagInterface;

/**
 * An array of expected or actual array values.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
abstract class AbstractArrayValues extends AbstractArrayResult implements TagInterface
{
    /**
     * @psalm-param ArrayLike $array
     */
    final public function __construct(iterable $array = [])
    {
        parent::__construct($array);
    }

    /**
     * @psalm-return non-empty-string
     */
    final public function tag(): string
    {
        StaticRandomStrings::familyTag(__NAMESPACE__.'\ArrayValues', 'c225435bd5434279f77fb3cddf138302a5c826ec');
    }
}

// vim: syntax=php sw=4 ts=4 et:
