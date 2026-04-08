<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Values\ExpectedValues;


/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
class ExpectedValuesSorting extends ExpectedValues implements SortingInterface
{
    /**
     * @var SorterInterface
     */
    private $sorter;

    /**
     * @param SorterInterface $sorter
     * @param mixed                  $input
     *
     * @psalm-param array|object     $input
     */
    public function __construct(SorterInterface $sorter, $input = [])
    {
        $this->sorter = $sorter;
        // The following if-else is only to make psalm 5.4.0 happy
        // See: https://github.com/vimeo/psalm/issues/9082
        if (is_array($input)) {
            parent::__construct($input);
        } else {
            parent::__construct($input);
        }
    }

    /**
     * @psalm-mutation-free
     */
    final public function getSorter(): SorterInterface
    {
        return $this->sorter;
    }
}

// vim: syntax=php sw=4 ts=4 et:
