<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Exporter;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Exporter\Exporter as SebastianBergmannExporter;
use Tailors\PHPUnit\Values\ActualValues;
use Tailors\PHPUnit\Values\ExpectedValues;

/**
 * @small
 * @covers \Tailors\PHPUnit\Exporter\Exporter
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class ExporterTest extends TestCase
{
    //
    //
    // TESTS
    //
    //

    public function testExtendsSebastianBergmannExporter(): void
    {
        self::assertInstanceOf(SebastianBergmannExporter::class, new Exporter());
    }

    //
    // export()
    //

    public function provExport(): array
    {
        $sebastianExporter = new SebastianBergmannExporter();
        $sebastianHandles = [
            null,               // #0
            'abc',              // #1
            123,                // #2
            [                   // #3
                'foo' => 'FOO',
            ],
        ];

        $cases = [];
        foreach ($sebastianHandles as $value) {
            $type = is_object($value) ? get_class($value) : gettype($value);
            $cases[sprintf('ExporterTest.php:%d (%s)', __LINE__, $type)] = [
                'argument' => $value,
                'expected' => $sebastianExporter->export($value),
            ];
        }

        $cases['ExporterTest.php:'.__LINE__] = [
            'arguments' => new ActualValues([]),
            'expected'  => 'values ()',
        ];

        $cases['ExporterTest.php:'.__LINE__] = [
            'arguments' => new ExpectedValues([]),
            'expected'  => 'values ()',
        ];

        $cases['ExporterTest.php:'.__LINE__] = [
            'arguments' => new ActualValues([
                'foo' => 'FOO',
            ]),
            'expected' => "values (\n".
                          "    'foo' => 'FOO'\n".
                          ')',
        ];

        $cases['ExporterTest.php:'.__LINE__] = [
            'arguments' => new ExpectedValues([
                'foo' => 'FOO',
            ]),
            'expected' => "values (\n".
                          "    'foo' => 'FOO'\n".
                          ')',
        ];

        return $cases;
    }

    /**
     * @dataProvider provExport
     *
     * @param mixed $argument
     */
    public function testExport($argument, string $expected): void
    {
        $exporter = new Exporter();
        self::assertSame($expected, $exporter->export($argument));
    }

    public function testExportHandlesCycle(): void
    {
        $exporter = new Exporter();
        $argument = new ActualValues([]);
        $argument['foo'] = $argument;

        $expected = "values (\n".
            "    'foo' => values\n".
            ')';
        self::assertSame($expected, $exporter->export($argument));
    }

    //
    // shortenedExport()
    //

    public function provShortenedExport(): array
    {
        $sebastianExporter = new SebastianBergmannExporter();
        $sebastianHandles = [
            null,               // #0
            'abc',              // #1
            123,                // #2
            new \StdClass(),    // #3
        ];

        $cases = [];
        foreach ($sebastianHandles as $value) {
            $type = is_object($value) ? get_class($value) : gettype($value);
            $cases[sprintf('ExporterTest.php:%d (%s)', __LINE__, $type)] = [
                'argument' => $value,
                'expected' => $sebastianExporter->shortenedExport($value),
            ];
        }

        $cases['ExporterTest.php:'.__LINE__] = [
            'arguments' => new ActualValues([]),
            'expected'  => 'values ()',
        ];

        $cases['ExporterTest.php:'.__LINE__] = [
            'arguments' => new ExpectedValues([]),
            'expected'  => 'values ()',
        ];

        $cases['ExporterTest.php:'.__LINE__] = [
            'arguments' => new ActualValues([
                'foo' => 'FOO',
            ]),
            'expected' => 'values (...)',
        ];

        $cases['ExporterTest.php:'.__LINE__] = [
            'arguments' => new ExpectedValues([
                'foo' => 'FOO',
            ]),
            'expected' => 'values (...)',
        ];

        return $cases;
    }

    /**
     * @dataProvider provShortenedExport
     *
     * @param mixed $argument
     */
    public function testShortenedExport($argument, string $expected): void
    {
        $exporter = new Exporter();
        self::assertSame($expected, $exporter->shortenedExport($argument));
    }
}
// vim: syntax=php sw=4 ts=4 et:
