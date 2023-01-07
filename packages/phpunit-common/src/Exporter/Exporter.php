<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Exporter;

use SebastianBergmann\Exporter\Exporter as SebastianBergmannExporter;
use SebastianBergmann\RecursionContext\Context;
use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * An exporter that handles ValuesInterface in a special way.
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class Exporter extends SebastianBergmannExporter
{
    /**
     * Recursive implementation of export.
     *
     * @param mixed   $value       The value to export
     * @param int     $indentation The indentation level of the 2nd+ line
     * @param Context $processed   Previously processed objects
     *
     * @return string
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function recursiveExport(&$value, $indentation, $processed = null)
    {
        if ($value instanceof ValuesInterface) {
            return $this->exportValues($value, $indentation, $processed);
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
        if ($value instanceof ValuesInterface) {
            return sprintf('values (%s)', count($this->toArray($value)) > 0 ? '...' : '');
        }

        return parent::shortenedExport($value);
    }

    /**
     * @param int     $indentation
     * @param Context $processed
     *
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    private function exportValues(ValuesInterface $value, $indentation, $processed = null): string
    {
        $whitespace = str_repeat(' ', 4 * $indentation);

        if (!$processed) {
            $processed = new Context();
        }

        $hash = $processed->contains($value);

        if ($hash) {
            return 'values';
        }

        $processed->add($value);
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

        return sprintf('values (%s)', $values);
    }
}

// vim: syntax=php sw=4 ts=4 et:
