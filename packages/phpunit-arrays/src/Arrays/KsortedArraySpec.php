<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\ArraySpec\AbstractArraySpec;
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @template-extends AbstractArraySpec<ArrayLike>
 */
final class KsortedArraySpec extends AbstractArraySpec
{
    /**
     * @psalm-param ArrayLike $array
     */
    public function __construct(iterable $array)
    {
        parent::__construct(new KsortedArrayFactory(), $array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
