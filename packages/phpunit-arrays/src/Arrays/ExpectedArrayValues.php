<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Values\ArrayValueSelector;
use Tailors\PHPUnit\Values\ValueSelectorInterface;
use Tailors\PHPUnit\Values\ValueSelectorWrapperInterface;

/**
 * An array of expected array values.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ExpectedArrayValues extends AbstractArrayValues implements ValueSelectorWrapperInterface
{
    private static ?ArrayValueSelector $valueSelector = null;

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
            self::$valueSelector = new ArrayValueSelector();
            // @codeCoverageIgnoreEnd
        }

        return self::$valueSelector;
    }
}

// vim: syntax=php sw=4 ts=4 et:
