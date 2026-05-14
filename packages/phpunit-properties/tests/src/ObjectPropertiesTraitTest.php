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
use Tailors\PHPUnit\Properties\ExpectedObjectProperties;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ObjectPropertiesTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ObjectPropertiesArgs = list{0: array|\Traversable}
 */
final class ObjectPropertiesTraitTest extends TestCase
{
    use ObjectPropertiesTrait;

    /**
     * @psalm-return iterable<string,array{args: ObjectPropertiesArgs, expect: mixed}>
     */
    public static function provExpectObjectProperties(): iterable
    {
        yield 'ObjectPropertiesTraitTest.php:'.__LINE__ => [
            'args'   => [[]],
            'expect' => [],
        ];

        yield 'ObjectPropertiesTraitTest.php:'.__LINE__ => [
            'args'   => [['a' => 'A']],
            'expect' => ['a' => 'A'],
        ];

        yield 'ObjectPropertiesTraitTest.php:'.__LINE__ => [
            'args'   => [new \ArrayObject(['o' => 'O'])],
            'expect' => ['o' => 'O'],
        ];
    }

    /**
     * @dataProvider provExpectObjectProperties
     *
     * @psalm-param ObjectPropertiesArgs $args
     * @psalm-param mixed                $expect
     */
    public function testExpectedObjectProperties(array $args, mixed $expect): void
    {
        $values = self::objectProperties(...$args);
        self::assertInstanceOf(ExpectedObjectProperties::class, $values);
        self::assertSame($expect, (array) $values);
    }
}

// vim: syntax=php sw=4 ts=4 et:
