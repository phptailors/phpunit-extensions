<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use SebastianBergmann\Exporter\ExportContext;
use SebastianBergmann\Exporter\Exporter;
use SebastianBergmann\Exporter\ObjectExporter;
use SebastianBergmann\Exporter\ObjectNotSupportedException;

if (interface_exists(ObjectExporter::class)) {
    /**
     * @internal This class is not covered by the backward compatibility promise
     *
     * @psalm-internal Tailors\PHPUnit
     */
    final class ExportableNameObjectExporter implements ObjectExporter
    {
        /**
         * @psalm-var array<class-string, array<int, int>>
         *
         * @psalm-suppress PossiblyUnusedProperty
         */
        public array $counters = [];

        public function handles(object $object): bool
        {
            return $object instanceof ExportableNameInterface;
        }

        public function export(object $object, Exporter $exporter, int $indentation, ExportContext $context): string
        {
            if (!$object instanceof ExportableNameInterface) {
                throw new ObjectNotSupportedException(sprintf('Object of type %s is not supported', $object::class));
            }

            $class = $object::class;

            if (!array_key_exists($class, $this->counters)) {
                $this->counters[$class] = [];
            }

            $objId = spl_object_id($object);

            if (!array_key_exists($objId, $this->counters[$class])) {
                $this->counters[$class][$objId] = count($this->counters[$class]);
            }

            $level = $this->counters[$class][$objId];

            $name = $class::exportableName();

            if (false !== $context->contains($object)) {
                return $name.' &'.((string) $level);
            }

            $context->add($object);

            // FIXME: remove when possible. See https://github.com/sebastianbergmann/exporter/issues/202 is fixed.
            if ($context->isBeingExportedByObjectExporter($object)) {
                $context->endExportByObjectExporter($object);
            }

            $array = $exporter->toArray($object);
            $buffer = '';

            if (count($array) > 0) {
                $whitespace = str_repeat(' ', 4 * $indentation);

                /** @psalm-var mixed $v */
                foreach ($array as $k => $v) {
                    $buffer .=
                        $whitespace
                        .'    '.
                        $exporter->export($k, $indentation)
                        .' => '.
                        $exporter->export($v, $indentation + 1, $context)
                        .",\n";
                }

                $buffer = "\n".$buffer.$whitespace;
            }

            return $name.' &'.((string) $level).' ('.$buffer.')';
        }
    }
}

// vim: syntax=php sw=4 ts=4 et:
