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
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
abstract class AbstractKsortedArray extends AbstractArrayResult implements TagInterface
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
        return StaticRandomStrings::familyTag(__NAMESPACE__.'\KsortedArray', 'e81092c9084f0813af075edd32d0beb2f3c774f3');
    }
}

// vim: syntax=php sw=4 ts=4 et:
