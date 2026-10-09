<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Common;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Exporter\Exporter as SebastianExporter;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Common\Exporter
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ExportArgs = list{0:mixed, 1?:bool}
 */
final class ExporterTest extends TestCase
{
    /**
     * @psalm-return iterable<string, list{ExportArgs, string}>
     */
    public static function provExport(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [[null], 'null'];

        yield basename(__FILE__).':'.__LINE__ => [[null, true], 'null'];

        yield basename(__FILE__).':'.__LINE__ => [[new \stdClass()], '{enable export of objects to see this value}'];

        yield basename(__FILE__).':'.__LINE__ => [[['foo' => ['bar' => new \stdClass()]]], '{enable export of objects to see this value}'];

        yield basename(__FILE__).':'.__LINE__ => [[new \stdClass(), true], 'stdClass Object %s'];
    }

    /**
     * @dataProvider provExport
     *
     * @psalm-param ExportArgs $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExport(array $args, string $format): void
    {
        $this->assertStringMatchesFormat($format, Exporter::export(...$args));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExportWithRecursiveArrayOfScalars(): void
    {
        $array = [];
        $array[0] = &$array;
        $format = (new SebastianExporter())->export($array);
        $this->assertStringMatchesFormat($format, Exporter::export($array));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExportWithRecursiveArrayWithObject(): void
    {
        $array = ['foo' => new \ArrayObject()];
        $array['foo'][0] = &$array;
        $format = (new SebastianExporter())->export($array);
        $this->assertStringMatchesFormat('{enable export of objects to see this value}', Exporter::export($array));
        $this->assertStringMatchesFormat($format, Exporter::export($array, true));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExporterWithExportableNameObject(): void
    {
        $top = new DummyNamedArray();
        $top['foo'] = [
            'bar' => new DummyNamedArray(),
            'rec' => $top,
        ];

        $expected = <<<'EOD'
        DummyNamedArray &0 (
            'foo' => Array &0 (
                'bar' => DummyNamedArray &1 ()
                'rec' => DummyNamedArray &0
            )
        )
        EOD;

        $this->assertSame($expected, Exporter::export($top, true));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExporterWithObjectLikeStringOfNonClass(): void
    {
        $data = 'Tailors\InExistent\Class Object #123 ()';
        $expected = (new SebastianExporter())->export($data);
        $this->assertSame($expected, Exporter::export($data, true));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExporterWithObjectLikeStringOfNonExportableName(): void
    {
        $data = self::class.' Object #123 ()';
        $expected = (new SebastianExporter())->export($data);
        $this->assertSame($expected, Exporter::export($data, true));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExporterWithObjectLikeStringOfExportableName(): void
    {
        $data = DummyNamedArray::class.' Object #123 ()';
        $expected = (new SebastianExporter())->export($data);
        $this->assertSame($expected, Exporter::export($data, true));
    }
}
// vim: syntax=php sw=4 ts=4 et:
