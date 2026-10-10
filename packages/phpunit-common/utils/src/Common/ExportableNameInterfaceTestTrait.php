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

/**
 * @internal This trait is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-require-extends TestCase
 *
 * @var TestCase $__phpactor__workaround__unused_import__TestCase
 */
trait ExportableNameInterfaceTestTrait
{
    /**
     * @psalm-return iterable<string, array{
     *  class: class-string<ExportableNameInterface>,
     *  name: mixed
     * }>
     */
    abstract public static function provExportableName(): iterable;

    /**
     * @psalm-return iterable<string, array{object: object}>
     */
    abstract public static function provImplementsExportableNameInterface(): iterable;

    /**
     * @dataProvider provExportableName
     *
     * @param mixed $name
     *
     * @psalm-param class-string<ExportableNameInterface> $class
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testExportableName(string $class, $name): void
    {
        self::assertSame($name, $class::exportableName());
    }

    /**
     * @dataProvider provImplementsExportableNameInterface
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    final public function testImplementsExportableNameInterface(object $object): void
    {
        self::assertInstanceOf(ExportableNameInterface::class, $object);
    }
}
