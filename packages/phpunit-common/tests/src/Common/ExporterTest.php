<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\Exporter\Exporter as SebastianExporter;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(Exporter::class)]
final class ExporterTest extends TestCase
{
    public static function provExport(): iterable
    {
        yield [[null], 'null'];

        yield [[null, true], 'null'];

        yield [[new \stdClass()], '{enable export of objects to see this value}'];

        yield [[['foo' => ['bar' => new \stdClass()]]], '{enable export of objects to see this value}'];

        yield [[new \stdClass(), true], 'stdClass Object %s'];
    }

    #[DataProvider('provExport')]
    public function testExport(array $args, string $format): void
    {
        $this->assertStringMatchesFormat($format, Exporter::export(...$args));
    }

    public function testExportWithRecursiveArrayOfScalars(): void
    {
        $array = [];
        $array[0] = &$array;
        $format = (new SebastianExporter())->export($array);
        $this->assertStringMatchesFormat($format, Exporter::export($array));
    }

    public function testExportWithRecursiveArrayWithObject(): void
    {
        $array = ['foo' => new \ArrayObject()];
        $array['foo'][0] = &$array;
        $format = (new SebastianExporter())->export($array);
        $this->assertStringMatchesFormat('{enable export of objects to see this value}', Exporter::export($array));
        $this->assertStringMatchesFormat($format, Exporter::export($array, true));
    }
}
// vim: syntax=php sw=4 ts=4 et:
