<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * @internal This interface is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
class Selection extends ExpectedValues implements SelectionInterface
{
    /**
     * @var ValueSelectorInterface
     */
    private $selector;

    /**
     * @param ValueSelectorInterface $selector
     * @param mixed                  $input
     * @psalm-param array|null|object $input
     */
    public function __construct(ValueSelectorInterface $selector, $input = [])
    {
        $this->selector = $selector;
        parent::__construct($input);
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
