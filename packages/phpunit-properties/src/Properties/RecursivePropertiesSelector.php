<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

/**
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursivePropertiesSelector implements RecursivePropertiesSelectorInterface
{
    /**
     * @var ExpectedPropertiesInterface
     */
    private $expected;

    public function __construct(ExpectedPropertiesInterface $expected)
    {
        $this->expected = $expected;
    }

    /**
     * @param mixed $subject
     */
    public function selectProperties($subject): ActualPropertiesInterface
    {
        return new ActualProperties($this->selectPropertiesArray($subject));
    }

    /**
     * @param mixed $subject
     */
    private function selectPropertiesArray($subject): array
    {
        $array = [];
        $selector = $this->expected->getPropertySelector();
        // order of keys in $array shall follow the given sequence in $this->expected
        /** @psalm-var mixed $expect */
        foreach ($this->expected as $key => $expect) {
            if ($selector->selectProperty($subject, $key, $actual)) {
                /** @psalm-var mixed */
                $array[$key] = $this->adjustActualValue($actual, $expect);
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
    private function adjustActualValue($actual, $expect)
    {
        if ($expect instanceof ExpectedPropertiesInterface) {
            return $this->adjustActualValueForExpectedProperties($actual, $expect);
        }
        if (is_array($expect) && is_array($actual)) {
            return $this->adjustActualArrayForExpectedArray($actual, $expect);
        }

        return $actual;
    }

    /**
     * @param mixed $actual
     *
     * @return mixed
     */
    private function adjustActualValueForExpectedProperties($actual, ExpectedPropertiesInterface $expect)
    {
        if ($expect->getPropertySelector()->canSelectFrom($actual)) {
            return (new RecursivePropertiesSelector($expect))->selectProperties($actual);
        }

        return $actual;
    }

    private function adjustActualArrayForExpectedArray(array $actual, array $expect): array
    {
        /** @psalm-var mixed $val */
        foreach ($actual as $key => &$val) {
            /** @psalm-var mixed */
            $val = self::adjustActualValue($val, $expect[$key]);
        }

        return $actual;
    }
}

// vim: syntax=php sw=4 ts=4 et:
