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
final class DummySelectionWrapper implements SelectionWrapperInterface
{
    /**
     * @var SelectionInterface
     */
    private $selection;

    public function __construct(SelectionInterface $selection)
    {
        $this->selection = $selection;
    }

    public function getSelection(): SelectionInterface
    {
        return $this->selection;
    }

    public function getValues(): ValuesInterface
    {
        return $this->selection;
    }
}
// vim: syntax=php sw=4 ts=4 et:
