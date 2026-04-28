<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\StaticAnalysis\HappyPath\ValueSelector;

use Tailors\PHPUnit\Values\ValueSelectorInterface;
use Tailors\PHPUnit\Values\ValueSelectorWrapperInterface;

final class DummyValueSelector implements ValueSelectorInterface
{
    /**
     * @param mixed $subject
     */
    public function supports($subject): bool
    {
        return false;
    }

    /**
     * @param mixed $subject
     * @param mixed $key
     * @param mixed $retval
     *
     * @psalm-param array-key $key
     *
     * @param-out mixed $retval
     */
    public function select($subject, $key, &$retval): bool
    {
        return false;
    }

    public function subject(): string
    {
        return '';
    }

    public function selectable(): string
    {
        return '';
    }
}

final class DummyValueSelectorWrapper implements ValueSelectorWrapperInterface
{
    /**
     * @var ValueSelectorInterface
     */
    public $valueSelector;

    public function __construct(ValueSelectorInterface $valueSelector)
    {
        $this->valueSelector = $valueSelector;
    }

    public function getValueSelector(): ValueSelectorInterface
    {
        return $this->valueSelector;
    }
}

function consume(): ValueSelectorInterface
{
    $wrapper = new DummyValueSelectorWrapper(new DummyValueSelector());

    return $wrapper->getValueSelector();
}

// vim: syntax=php sw=4 ts=4 et:
