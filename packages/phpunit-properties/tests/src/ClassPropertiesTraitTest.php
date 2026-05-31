<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Properties\ExpectedClassProperties;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ClassPropertiesTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike           = iterable<array-key, mixed>
 * @psalm-type ClassPropertiesArgs = list{0: ArrayLike}
 */
final class ClassPropertiesTraitTest extends TestCase
{
    use ClassPropertiesTrait;

    /**
     * @psalm-return iterable<string,array{args: ClassPropertiesArgs, expect: mixed}>
     */
    public static function provExpectClassProperties(): iterable
    {
        yield 'ClassPropertiesTraitTest.php:'.__LINE__ => [
            'args'   => [[]],
            'expect' => [],
        ];

        yield 'ClassPropertiesTraitTest.php:'.__LINE__ => [
            'args'   => [['a' => 'A']],
            'expect' => ['a' => 'A'],
        ];

        yield 'ClassPropertiesTraitTest.php:'.__LINE__ => [
            'args'   => [new \ArrayObject(['o' => 'O'])],
            'expect' => ['o' => 'O'],
        ];
    }

    /**
     * @dataProvider provExpectClassProperties
     *
     * @param mixed $expect
     *
     * @psalm-param ClassPropertiesArgs $args
     */
    public function testExpectedClassProperties(array $args, $expect): void
    {
        $values = self::classProperties(...$args);
        self::assertInstanceOf(ExpectedClassProperties::class, $values);
        self::assertSame($expect, (array) $values);
    }
}

// vim: syntax=php sw=4 ts=4 et:
