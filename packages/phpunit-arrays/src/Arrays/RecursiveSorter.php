<?php declare(strict_types=1);RecursiveSor

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\Values\ActualValues;
use Tailors\PHPUnit\Values\ValuesInterface;


/**
 * Key-sorts actual values from a $subject according to expected values.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveSorter implements RecursiveSorterInterface
{
    /**
     * @var SortingInterface
     */
    private $sorting;

    public function __construct(SortingInterface $sorting)
    {
        $this->sorting = $sorting;
    }

    /**
     * Select an array of values from $subject.
     *
     * @param mixed $subject
     */
    public function sorted($subject): ValuesInterface
    {
        return new ActualValues($this->sortedArray($subject));
    }

    /**
     * @param mixed $subject
     *
     * @return array
     */
    private function sortedArray($subject): array
    {
        $sorter = $this->sorting->getSorter();

        $array = $sorter->sorted($subject);

        /** @psalm-var mixed $expect */
        foreach ($this->sorting as $key => $expect) {
            if (\array_key_exists($key, $array)) {
                $actual = $array[$key];
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
        if ($expect instanceof SortingWrapperInterface) {
            $expect = $expect->getSorting();
        }

        if ($expect instanceof SortingInterface) {
            return self::adjustActualValueToSorting($actual, $expect);
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
    private static function adjustActualValueToSorting($actual, SortingInterface $sorting)
    {
        if ($sorting->getSorter()->supports($actual)) {
            return (new RecursiveSorter($sorting))->sorted($actual);
        }

        return $actual;
    }

    /**
     * @param array $actual
     * @param array $expect
     */
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
