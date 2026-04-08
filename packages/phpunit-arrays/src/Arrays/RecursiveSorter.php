<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Values\ActualValues;
use Tailors\PHPUnit\Values\ExpectedValues;
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
     *
     * @throws InvalidArgumentException
     */
    public function sorted($subject): ValuesInterface
    {
        $array = $this->sortedArray($subject);

        if ($subject instanceof ValuesInterface && !$subject->actual()) {
            return new ExpectedValues($array);
        }

        return new ActualValues($array);
    }

    /**
     * @param mixed $subject
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    private function sortedArray($subject): array
    {
        $sorter = $this->sorting->getSorter();

        $array = $sorter->sorted($subject);

        /** @psalm-var mixed $expect */
        foreach ($this->sorting as $key => $expect) {
            if (\array_key_exists($key, $array)) {
                /** @psalm-var mixed */
                $value = $array[$key];

                /** @psalm-var mixed */
                $array[$key] = self::adjustValueToExpectedValue($value, $expect);
            }
        }

        return $array;
    }

    /**
     * @param mixed $value
     * @param mixed $expect
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    private static function adjustValueToExpectedValue($value, $expect)
    {
        if ($expect instanceof SortingWrapperInterface) {
            $expect = $expect->getSorting();
        }

        if ($expect instanceof SortingInterface) {
            return self::adjustValueToSorting($value, $expect);
        }

        if (is_array($expect) && is_array($value)) {
            return self::adjustArrayToArray($value, $expect);
        }

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    private static function adjustValueToSorting($value, SortingInterface $sorting)
    {
        if ($sorting->getSorter()->supports($value)) {
            return (new self($sorting))->sorted($value);
        }

        return $value;
    }

    /**
     * @param array $value
     * @param array $expect
     */
    private static function adjustArrayToArray(array $value, array $expect): array
    {
        /** @psalm-var mixed $val */
        foreach ($value as $key => &$val) {
            /** @psalm-var mixed */
            $val = self::adjustValueToExpectedValue($val, $expect[$key]);
        }

        return $value;
    }
}

// vim: syntax=php sw=4 ts=4 et:
