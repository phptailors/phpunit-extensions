<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use Tailors\PHPUnit\Selector\ObjectPropertySelector;
use Tailors\PHPUnit\Selector\ValueSelectorInterface;
use Tailors\PHPUnit\Selector\ValueSelectorWrapperInterface;

/**
 * An array of expected object properties.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedObjectProperties extends AbstractObjectProperties implements ValueSelectorWrapperInterface
{
    /**
     * @var ?ObjectPropertySelector
     */
    private static $valueSelector;

    /**
     * @psalm-mutation-free
     */
    public function actual(): bool
    {
        return false;
    }

    public function getValueSelector(): ValueSelectorInterface
    {
        if (null === self::$valueSelector) {
            // @codeCoverageIgnoreStart
            self::$valueSelector = new ObjectPropertySelector();
            // @codeCoverageIgnoreEnd
        }

        return self::$valueSelector;
    }
}

// vim: syntax=php sw=4 ts=4 et:
