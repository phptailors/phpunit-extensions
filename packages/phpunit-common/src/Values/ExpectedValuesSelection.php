<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
class ExpectedValuesSelection extends ExpectedValues implements SelectionInterface
{
    /**
     * @var ValueSelectorInterface
     */
    private $selector;

    /**
     * @param ValueSelectorInterface $selector
     * @param mixed                  $input
     *
     * @psalm-param array|object     $input
     */
    public function __construct(ValueSelectorInterface $selector, $input = [])
    {
        $this->selector = $selector;
        // The folowing if-else is only to make psalm 5.4.0 happy
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
    final public function getSelector(): ValueSelectorInterface
    {
        return $this->selector;
    }
}

// vim: syntax=php sw=4 ts=4 et:
