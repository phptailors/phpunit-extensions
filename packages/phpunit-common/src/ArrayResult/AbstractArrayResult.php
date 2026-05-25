<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ArrayResult;

use Tailors\PHPUnit\Result\ResultInterface;

/**
 * An array of expected values.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @template-extends \ArrayObject<array-key,mixed>
 */
abstract class AbstractArrayResult extends \ArrayObject implements ResultInterface
{
    /**
     * @param array|\Traversable $array
     */
    protected function __construct($array = [])
    {
        if (!is_array($array)) {
            $array = iterator_to_array($array);
        }

        parent::__construct($array);
    }
}

// vim: syntax=php sw=4 ts=4 et:
