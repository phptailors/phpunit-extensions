<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

use Tailors\PHPUnit\Common\StaticRandomStrings;
use Tailors\PHPUnit\Common\TagInterface;

/**
 * An array of expected or actual values (generic).
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
abstract class AbstractGenericArrayResult extends AbstractArrayResult implements TagInterface
{
    /**
     * @var ?string
     *
     * @psalm-var ?non-falsy-string
     */
    private $tag;

    /**
     * @psalm-param ArrayLike         $array
     * @psalm-param ?non-falsy-string $tag
     */
    final public function __construct(iterable $array = [], ?string $tag = null)
    {
        $this->tag = $tag;

        parent::__construct($array);
    }

    /**
     * @psalm-return non-empty-string
     */
    final public function tag(): string
    {
        return $this->tag ?? $this->familyTag();
    }

    /**
     * @psalm-return non-falsy-string
     */
    private function familyTag(): string
    {
        return StaticRandomStrings::familyTag(__NAMESPACE__.'\GenericArrayResult', 'b431aa5424c80003a46c769389f28d0bcde7bf21');
    }
}

// vim: syntax=php sw=4 ts=4 et:
