<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use SebastianBergmann\Exporter\Exporter as SebastianExporter;
use SebastianBergmann\RecursionContext\Context;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class Exporter
{
    /**
     * @psalm-suppress MixedInferredReturnType
     *
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    public static function export($value, bool $exportObjects = false): string
    {
        // @codeCoverageIgnoreStart

        if (class_exists(\PHPUnit\Util\Exporter::class)) {
            /**
             * @psalm-suppress InternalClass
             * @psalm-suppress InternalMethod
             * @psalm-suppress MixedArgument
             */
            return self::postprocessOutput(\PHPUnit\Util\Exporter::export($value, $exportObjects));
        }

        if (self::isExportable($value) || $exportObjects) {
            return self::postprocessOutput((new SebastianExporter())->export($value));
        }

        return '{enable export of objects to see this value}';
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     *
     * @psalm-param mixed $value
     */
    private static function isExportable(&$value, ?Context $context = null): bool
    {
        // @codeCoverageIgnoreStart

        if (is_scalar($value) || null === $value) {
            return true;
        }

        if (!is_array($value)) {
            return false;
        }

        if (!$context) {
            $context = new Context();
        }

        if (false !== $context->contains($value)) {
            return true;
        }

        $array = $value;
        $context->add($value);

        foreach ($array as &$_value) {
            if (!self::isExportable($_value, $context)) {
                return false;
            }
        }

        return true;
        // @codeCoverageIgnoreEnd
    }

    private static function postprocessOutput(string $output): string
    {
        return self::handleExportableNames($output);
    }

    private static function handleExportableNames(string $output): string
    {
        $counters = [];

        /** @psalm-var string */
        return preg_replace_callback(
            '/(\w+(?:\\\\\w+)*)\s+Object\s+(#\d+|&[0-9a-fA-f]+)/m',
            function (array $matches) use (&$counters): string {
                $class = $matches[1];

                if (!class_exists($class)) {
                    return $matches[0];
                }

                if (!is_subclass_of($class, ExportableNameInterface::class, true)) {
                    return $matches[0];
                }

                if (!array_key_exists($class, $counters)) {
                    $counters[$class] = [];
                }

                $objId = $matches[2];
                if (!array_key_exists($objId, $counters[$class])) {
                    $counters[$class][$objId] = count($counters[$class]);
                }

                $name = $class::exportableName();

                $level = $counters[$class][$objId];

                return $name.' &'.((string) $level);
            },
            $output
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
