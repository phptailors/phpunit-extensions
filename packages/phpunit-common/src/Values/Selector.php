<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use Tailors\PHPUnit\InvalidArgumentException;

/**
 * @internal This interface is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class Selector implements SelectorInterface
{
    /**
     * @var ValueSelectorInterface
     */
    private $valueSelector;

    public function __construct(ValueSelectorInterface $valueSelector)
    {
        $this->valueSelector = $valueSelector;
    }

    /**
     * @param mixed $subject
     *
     * @throws InvalidArgumentException
     */
    public function select($subject, SelectionInterface $selection, ValuesInterface $output): void
    {
        foreach ($selection as $path) {
            if (self::selectValue($subject, $path, $output))
        }
    }

    /**
     * @param mixed $subject
     * @param list<array-key> $path
     * @param mixed $retval
     */
    private function selectValue($subject, array $path, &$retval): bool
    {
        if (0 === count($path)) {
            return false;
        }

        $current = $subject;

        $top = array_pop($path);
        foreach ($path as $key) {
            if (!$this->valueSelector->supports($current)) {
                return false;
            }

            if (!$this->valueSelector->select($current, $key, $current)) {
                return false;
            }
        }

        $retval = $
    }
}

// vim: syntax=php sw=4 ts=4 et:
