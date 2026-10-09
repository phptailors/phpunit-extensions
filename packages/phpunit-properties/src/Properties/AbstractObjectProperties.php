<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\ArrayResult\AbstractArrayResult;
use Tailors\PHPUnit\Common\ExportableNameInterface;
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
abstract class AbstractObjectProperties extends AbstractArrayResult implements TagInterface, ExportableNameInterface
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
        return StaticRandomStrings::familyTag(__NAMESPACE__.'\ObjectProperties', '0f1d9297ad4259f9b8926b83329b4d82448592cd');
    }

    /**
     * @psalm-pure
     */
    final public static function exportableName(): string
    {
        return 'ObjectProperties';
    }
}

// vim: syntax=php sw=4 ts=4 et:
