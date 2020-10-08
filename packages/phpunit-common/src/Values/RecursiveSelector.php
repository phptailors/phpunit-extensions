<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

/**
 * Selects actual values from a $subject according to expected values.
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveSelector implements RecursiveSelectorInterface
{
    /**
     * @var SelectionInterface
     */
    private $selection;

    public function __construct(SelectionInterface $selection)
    {
        $this->selection = $selection;
    }

    /**
     * Select an array of values from $subject.
     *
     * @param mixed $subject
     */
    public function select($subject): ValuesInterface
    {
        return new ActualValues($this->selectArray($subject));
    }

    /**
     * @param mixed $subject
     */
    private function selectArray($subject): array
    {
        $array = [];
        $selector = $this->selection->getSelector();

        // order of keys in $array shall follow that of $this->selection
        /** @psalm-var mixed $expect */
        foreach ($this->selection as $key => $expect) {
            if ($selector->select($subject, $key, $actual)) {
                /** @psalm-var mixed */
                $array[$key] = self::adjustActualValueToExpectedValue($actual, $expect);
            }
        }

        return $array;
    }

    /**
     * @param mixed $actual
     * @param mixed $expect
     *
     * @return mixed
     */
    private static function adjustActualValueToExpectedValue($actual, $expect)
    {
        if ($expect instanceof SelectionAggregateInterface) {
            $expect = $expect->getSelection();
        }
        if ($expect instanceof SelectionInterface) {
            return self::adjustActualValueToSelection($actual, $expect);
        }
        if (is_array($expect) && is_array($actual)) {
            return self::adjustActualValueToArray($actual, $expect);
        }

        return $actual;
    }

    /**
     * @param mixed $actual
     *
     * @return mixed
     */
    private static function adjustActualValueToSelection($actual, SelectionInterface $selection)
    {
        if ($selection->getSelector()->supports($actual)) {
            return (new RecursiveSelector($selection))->select($actual);
        }

        return $actual;
    }

    private static function adjustActualValueToArray(array $actual, array $expect): array
    {
        /** @psalm-var mixed $val */
        foreach ($actual as $key => &$val) {
            /** @psalm-var mixed */
            $val = self::adjustActualValueToExpectedValue($val, $expect[$key]);
        }

        return $actual;
    }
}

// vim: syntax=php sw=4 ts=4 et:
