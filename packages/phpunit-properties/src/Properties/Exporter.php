<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * Distributed under MIT license.
 */

namespace Tailors\PHPUnit\Properties;

use SebastianBergmann\Exporter\Exporter as SebastianBergmannExporter;
use SebastianBergmann\RecursionContext\Context;

/**
 * An exporter that handles PropertiesInterface in a special way.
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class Exporter extends SebastianBergmannExporter
{
    public function describe(PropertiesInterface $properties): string
    {
        $header = 'Properties';
        if ($properties instanceof ExpectedPropertiesInterface) {
            $header .= ' <Expect>';
        } elseif ($properties instanceof ActualPropertiesInterface) {
            $header .= ' <Actual>';
        }

        return $header;
    }

    /**
     * Recursive implementation of export.
     *
     * @param mixed   $value       The value to export
     * @param int     $indentation The indentation level of the 2nd+ line
     * @param Context $processed   Previously processed objects
     *
     * @return string
     */
    public function recursiveExport(&$value, $indentation, $processed = null)
    {
        if ($value instanceof PropertiesInterface) {
            return $this->exportProperties($value, $indentation, $processed);
        }

        return parent::recursiveExport($value, $indentation, $processed);
    }

    /**
     * Exports a value into a single-line string.
     *
     * The output of this method is similar to the output of
     * SebastianBergmann\Exporter\Exporter::export().
     *
     * Newlines are replaced by the visible string '\n'.
     * Contents of arrays and objects (if any) are replaced by '...'.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function shortenedExport($value)
    {
        if ($value instanceof PropertiesInterface) {
            return sprintf(
                '%s (%s)',
                $this->describe($value),
                count($this->toArray($value)) > 0 ? '...' : ''
            );
        }

        return parent::shortenedExport($value);
    }

    /**
     * @param int     $indentation
     * @param Context $processed
     */
    private function exportProperties(PropertiesInterface $value, $indentation, $processed = null): string
    {
        $whitespace = str_repeat(' ', (int) (4 * $indentation));

        if (!$processed) {
            $processed = new Context();
        }

        $hash = $processed->contains($value);

        if ($hash) {
            return $this->describe($value);
        }

        $hash = $processed->add($value);
        $values = '';
        $array = $this->toArray($value);

        if (count($array) > 0) {
            /** @psalm-var mixed $v */
            foreach ($array as $k => $v) {
                $values .= sprintf(
                    '%s    %s => %s'."\n",
                    $whitespace,
                    $this->recursiveExport($k, $indentation),
                    $this->recursiveExport($v, $indentation + 1, $processed)
                );
            }

            $values = "\n".$values.$whitespace;
        }

        return sprintf('%s (%s)', $this->describe($value), $values);
    }
}

// vim: syntax=php sw=4 ts=4 et:
