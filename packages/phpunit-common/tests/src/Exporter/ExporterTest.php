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
use Tailors\PHPUnit\Values\ValuesInterface;

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
    // describe()
    //

    public function provDescribe(): array
    {
        $expect = $this->createMock(ValuesInterface::class);
        $actual = $this->createMock(ValuesInterface::class);

        $expect->expects($this->any())
            ->method('actual')
            ->willReturn(false)
        ;

        $actual->expects($this->any())
            ->method('actual')
            ->willReturn(true)
        ;

        return [
            'ExporterTest.php:'.__LINE__ => [
                'argument' => $expect,
                'expected' => 'values <expect>',
            ],

            'ExporterTest.php:'.__LINE__ => [
                'argument' => $actual,
                'expected' => 'values <actual>',
            ],
        ];
    }

    /**
     * @dataProvider provDescribe
     */
    public function testDescribe(ValuesInterface $argument, string $expected): void
    {
        $exporter = new Exporter();
        self::assertSame($expected, $exporter->describe($argument));
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
            $cases[] = [
                'argument' => $value,
                'expected' => $sebastianExporter->export($value),
            ];
        }

        // #4
        $cases[] = [
            'arguments' => new ActualValues([]),
            'expected'  => 'values <actual> ()',
        ];

        // #5
        $cases[] = [
            'arguments' => new ExpectedValues([]),
            'expected'  => 'values <expect> ()',
        ];

        // #6
        $cases[] = [
            'arguments' => new ActualValues([
                'foo' => 'FOO',
            ]),
            'expected' => "values <actual> (\n".
                          "    'foo' => 'FOO'\n".
                          ')',
        ];

        // #7
        $cases[] = [
            'arguments' => new ExpectedValues([
                'foo' => 'FOO',
            ]),
            'expected' => "values <expect> (\n".
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

        $expected = "values <actual> (\n".
            "    'foo' => values <actual>\n".
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
            $cases[] = [
                'argument' => $value,
                'expected' => $sebastianExporter->shortenedExport($value),
            ];
        }

        // #4
        $cases[] = [
            'arguments' => new ActualValues([]),
            'expected'  => 'values <actual> ()',
        ];

        // #5
        $cases[] = [
            'arguments' => new ExpectedValues([]),
            'expected'  => 'values <expect> ()',
        ];

        // #6
        $cases[] = [
            'arguments' => new ActualValues([
                'foo' => 'FOO',
            ]),
            'expected' => 'values <actual> (...)',
        ];

        // #7
        $cases[] = [
            'arguments' => new ExpectedValues([
                'foo' => 'FOO',
            ]),
            'expected' => 'values <expect> (...)',
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
