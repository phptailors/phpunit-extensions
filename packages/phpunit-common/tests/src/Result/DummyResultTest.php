<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Result;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\TypesInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Result\DummyResult
 * @covers \Tailors\PHPUnit\Result\DummyResultTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class DummyResultTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultInterface(): void
    {
        $this->assertInstanceOf(ResultInterface::class, new DummyResult(false));
    }

    /**
     * @psalm-return iterable<string, array{
     *      actual: bool
     * }>
     */
    public static function provDummyResult(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'actual' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'actual' => true,
        ];
    }

    /**
     * @dataProvider provDummyResult
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testDummyResult(bool $actual): void
    {
        $dummyResult = new DummyResult($actual);

        $this->assertSame($actual, $dummyResult->actual());
    }
}
