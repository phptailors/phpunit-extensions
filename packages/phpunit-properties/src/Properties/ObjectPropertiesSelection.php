<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\ArraySpec\AbstractArraySelectionSpec;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\ValueSelector\ObjectPropertySelector;

/**
 * An array of expected class properties.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @template-extends AbstractArraySelectionSpec<ArrayLike, object>
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class ObjectPropertiesSelection extends AbstractArraySelectionSpec
{
    /**
     * @psalm-param ArrayLike $array
     */
    public function __construct(iterable $array)
    {
        parent::__construct(new ObjectPropertiesFactory(), new ObjectPropertySelector(), $array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
