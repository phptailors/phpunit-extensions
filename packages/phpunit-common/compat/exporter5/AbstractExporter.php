<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Exporter;

use SebastianBergmann\RecursionContext\Context;
use SplObjectStorage;

abstract class AbstractExporter
{
    /**
     * Exports a value as a string.
     *
     * The output of this method is similar to the output of print_r(), but
     * improved in various aspects:
     *
     *  - NULL is rendered as "null" (instead of "")
     *  - TRUE is rendered as "true" (instead of "1")
     *  - FALSE is rendered as "false" (instead of "")
     *  - Strings are always quoted with single quotes
     *  - Carriage returns and newlines are normalized to \n
     *  - Recursion and repeated rendering is treated properly
     */
    public function export(mixed $value, int $indentation = 0): string
    {
        return $this->recursiveExport($value, $indentation);
    }

    public function shortenedRecursiveExport(array &$data, Context $context = null): string
    {
        $result = [];
        $exporter = new self();

        if (!$context) {
            $context = new Context();
        }

        $array = $data;

        // @noinspection UnusedFunctionResultInspection
        $context->add($data);

        foreach ($array as $key => $value) {
            if (\is_array($value)) {
                if (false !== $context->contains($data[$key])) {
                    $result[] = '*RECURSION*';
                } else {
                    $result[] = \sprintf('[%s]', $this->shortenedRecursiveExport($data[$key], $context));
                }
            } else {
                $result[] = $exporter->shortenedExport($value);
            }
        }

        return \implode(', ', $result);
    }

    /**
     * Exports a value into a single-line string.
     *
     * The output of this method is similar to the output of
     * SebastianBergmann\Exporter\Exporter::export().
     *
     * Newlines are replaced by the visible string '\n'.
     * Contents of arrays and objects (if any) are replaced by '...'.
     */
    public function shortenedExport(mixed $value): string
    {
        if (\is_string($value)) {
            $string = \str_replace("\n", '', $this->export($value));

            if (\mb_strlen($string) > 40) {
                return \mb_substr($string, 0, 30).'...'.\mb_substr($string, -7);
            }

            return $string;
        }

        if ($value instanceof \BackedEnum) {
            return \sprintf(
                '%s Enum (%s, %s)',
                $value::class,
                $value->name,
                $this->export($value->value),
            );
        }

        if ($value instanceof \UnitEnum) {
            return \sprintf(
                '%s Enum (%s)',
                $value::class,
                $value->name,
            );
        }

        if (\is_object($value)) {
            return \sprintf(
                '%s Object (%s)',
                $value::class,
                \count($this->toArray($value)) > 0 ? '...' : '',
            );
        }

        if (\is_array($value)) {
            return \sprintf(
                '[%s]',
                \count($value) > 0 ? '...' : '',
            );
        }

        return $this->export($value);
    }

    /**
     * Converts an object to an array containing all of its private, protected
     * and public properties.
     */
    public function toArray(mixed $value): array
    {
        if (!\is_object($value)) {
            return (array) $value;
        }

        $array = [];

        foreach ((array) $value as $key => $val) {
            // Exception traces commonly reference hundreds to thousands of
            // objects currently loaded in memory. Including them in the result
            // has a severe negative performance impact.
            if ("\0Error\0trace" === $key || "\0Exception\0trace" === $key) {
                continue;
            }

            // properties are transformed to keys in the following way:
            // private   $propertyName => "\0ClassName\0propertyName"
            // protected $propertyName => "\0*\0propertyName"
            // public    $propertyName => "propertyName"
            if (\preg_match('/^\0.+\0(.+)$/', (string) $key, $matches)) {
                $key = $matches[1];
            }

            // See https://github.com/php/php-src/commit/5721132
            if ("\0gcdata" === $key) {
                continue;
            }

            $array[$key] = $val;
        }

        // Some internal classes like SplObjectStorage do not work with the
        // above (fast) mechanism nor with reflection in Zend.
        // Format the output similarly to print_r() in this case
        if ($value instanceof \SplObjectStorage) {
            foreach ($value as $_value) {
                $array['Object #'.\spl_object_id($_value)] = [
                    'obj' => $_value,
                    'inf' => $value->getInfo(),
                ];
            }

            $value->rewind();
        }

        return $array;
    }

    protected function recursiveExport(mixed &$value, int $indentation, ?Context $processed = null): string
    {
        if (null === $value) {
            return 'null';
        }

        if (true === $value) {
            return 'true';
        }

        if (false === $value) {
            return 'false';
        }

        if (\is_float($value)) {
            $precisionBackup = \ini_get('precision');

            \ini_set('precision', '-1');

            try {
                $valueStr = (string) $value;

                if ((string) (int) $value === $valueStr) {
                    return $valueStr.'.0';
                }

                return $valueStr;
            } finally {
                \ini_set('precision', $precisionBackup);
            }
        }

        if ('resource (closed)' === \gettype($value)) {
            return 'resource (closed)';
        }

        if (\is_resource($value)) {
            return \sprintf(
                'resource(%d) of type (%s)',
                $value,
                \get_resource_type($value),
            );
        }

        if ($value instanceof \BackedEnum) {
            return \sprintf(
                '%s Enum #%d (%s, %s)',
                $value::class,
                \spl_object_id($value),
                $value->name,
                $this->export($value->value, $indentation),
            );
        }

        if ($value instanceof \UnitEnum) {
            return \sprintf(
                '%s Enum #%d (%s)',
                $value::class,
                \spl_object_id($value),
                $value->name,
            );
        }

        if (\is_string($value)) {
            // Match for most non-printable chars somewhat taking multibyte chars into account
            if (\preg_match('/[^\x09-\x0d\x1b\x20-\xff]/', $value)) {
                return 'Binary String: 0x'.\bin2hex($value);
            }

            return "'".
            \str_replace(
                '<lf>',
                "\n",
                \str_replace(
                    ["\r\n", "\n\r", "\r", "\n"],
                    ['\r\n<lf>', '\n\r<lf>', '\r<lf>', '\n<lf>'],
                    $value,
                ),
            ).
            "'";
        }

        $whitespace = \str_repeat(' ', 4 * $indentation);

        if (!$processed) {
            $processed = new Context();
        }

        if (\is_array($value)) {
            if (($key = $processed->contains($value)) !== false) {
                return 'Array &'.$key;
            }

            $array = $value;
            $key = $processed->add($value);
            $values = '';

            if (\count($array) > 0) {
                foreach ($array as $k => $v) {
                    $values .=
                        $whitespace
                        .'    '.
                        $this->recursiveExport($k, $indentation)
                        .' => '.
                        $this->recursiveExport($value[$k], $indentation + 1, $processed)
                        .",\n";
                }

                $values = "\n".$values.$whitespace;
            }

            return 'Array &'.(string) $key.' ['.$values.']';
        }

        if (\is_object($value)) {
            $class = $value::class;

            if ($processed->contains($value)) {
                return $class.' Object #'.\spl_object_id($value);
            }

            $processed->add($value);
            $values = '';
            $array = $this->toArray($value);

            if (\count($array) > 0) {
                foreach ($array as $k => $v) {
                    $values .=
                        $whitespace
                        .'    '.
                        $this->recursiveExport($k, $indentation)
                        .' => '.
                        $this->recursiveExport($v, $indentation + 1, $processed)
                        .",\n";
                }

                $values = "\n".$values.$whitespace;
            }

            return $class.' Object #'.\spl_object_id($value).' ('.$values.')';
        }

        return \var_export($value, true);
    }
}

// vim: syntax=php sw=4 ts=4 et:
