<?php declare(strict_types=1);

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
     * @var SorterInterface
     */
    private $sorter;

    public function __construct(SorterInterface $sorter)
    {
        $this->sorter = $sorter;
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
     */
    private function sortedArray($subject): array
    {
        $array = $this->sorter->sorted($subject);


        return $array;
//        $array = [];
//        $selector = $this->selection->getSorter();
//
//        // order of keys in $array shall follow that of $this->selection
//        /** @psalm-var mixed $expect */
//        foreach ($this->selection as $key => $expect) {
//            if ($selector->select($subject, $key, $actual)) {
//                /** @psalm-var mixed */
//                $array[$key] = self::adjustActualValueToExpectedValue($actual, $expect);
//            }
//        }
//
//        return $array;
    }

    /**
     * @param mixed $actual
     * @param mixed $expect
     *
     * @return mixed
     */
    private static function adjustActualValueToExpectedValue($actual, $expect)
    {
//        if ($expect instanceof SelectionWrapperInterface) {
//            $expect = $expect->getSelection();
//        }
//        if ($expect instanceof SelectionInterface) {
//            return self::adjustActualValueToSelection($actual, $expect);
//        }
//        if (is_array($expect) && is_array($actual)) {
//            return self::adjustActualValueToArray($actual, $expect);
//        }
//
//        return $actual;
    }

    /**
     * @param mixed $actual
     *
     * @return mixed
     */
    private static function adjustActualValueToSelection($actual/*, SelectionInterface $selection*/)
    {
        if ($selection->getSorter()->supports($actual)) {
            return (new RecursiveSorter($selection))->select($actual);
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
