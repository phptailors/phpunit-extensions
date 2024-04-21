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

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class Exporter
{
    /**
     * @psalm-suppress MixedInferredReturnType
     */
    public static function export(mixed $value, bool $exportObjects = false): string
    {
        // @codeCoverageIgnoreStart

        if (self::isExportable($value) || $exportObjects) {
            return (new SebastianExporter())->export($value);
        }

        return '{enable export of objects to see this value}';
        // @codeCoverageIgnoreEnd
    }

    private static function isExportable(mixed &$value, ?Context $context = null): bool
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
}

// vim: syntax=php sw=4 ts=4 et:
