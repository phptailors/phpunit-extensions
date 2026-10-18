<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Common\AbstractArraySelection;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\ValueSelector\ArrayValueSelector;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @template-extends AbstractArraySelection<ArrayLike, array|\ArrayAccess>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class ArrayValuesSelection extends AbstractArraySelection
{
    /**
     * @psalm-param ArrayLike $array
     */
    public function __construct(iterable $array)
    {
        parent::__construct(new ArrayValuesFactory(), new ArrayValueSelector(), $array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
