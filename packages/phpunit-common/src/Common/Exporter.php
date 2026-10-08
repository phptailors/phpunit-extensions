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
use Tailors\PHPUnit\Result\ResultInterface;

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
             * @psalm-suppress MixedReturnStatement
             */
            return self::captureResultObjects(\PHPUnit\Util\Exporter::export($value, $exportObjects));
        }

        if (self::isExportable($value) || $exportObjects) {
            return self::captureResultObjects((new SebastianExporter())->export($value));
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

    private static function captureResultObjects(string $output): string
    {
        $counters = [];

        return preg_replace_callback(
            '/^(\s*)(Tailors\\\\PHPUnit(?:\\\\\w+)*\\\\(?:Expected|Actual)(\w+)) Object (?:#\d+|&[0-9a-fA-f]+)/m',
            function (array $matches) use (&$counters): string {
                if (!class_exists($matches[2])) {
                    return $matches[0];
                }

                $class = $matches[2];
                $implements = class_implements($class);
                if (null === ($implements[ResultInterface::class] ?? null)) {
                    return $matches[0];
                }

                if (!array_key_exists($class, $counters)) {
                    $counters[$class] = 0;
                }

                $level = $counters[$class]++;
                return $matches[1].$matches[3].' #'.((string) $level);
            },
            $output
        );
    }
}

// vim: syntax=php sw=4 ts=4 et:
