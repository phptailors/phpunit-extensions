<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-extends \ArrayObject<array-key,mixed>
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
abstract class AbstractArrayObject extends \ArrayObject
{
    /**
     * @psalm-param ArrayLike $array
     */
    protected function __construct(iterable $array = [])
    {
        if (!is_array($array)) {
            $array = iterator_to_array($array);
        }

        parent::__construct($array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
