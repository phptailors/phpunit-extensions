<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit;

use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Arrays\ExpectedArrayValues;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayValuesArgs = list{0: array|\Traversable}
 */
#[CoversTrait(ArrayValuesTrait::class)]
#[Small]
final class ArrayValuesTraitTest extends TestCase
{
    use ArrayValuesTrait;

    /**
     * @psalm-return iterable<string,array{args: ArrayValuesArgs, expect: mixed}>
     */
    public static function provExpectArrayValues(): iterable
    {
        yield 'ArrayValuesTraitTest.php:'.__LINE__ => [
            'args'   => [[]],
            'expect' => [],
        ];

        yield 'ArrayValuesTraitTest.php:'.__LINE__ => [
            'args'   => [['a' => 'A']],
            'expect' => ['a' => 'A'],
        ];

        yield 'ArrayValuesTraitTest.php:'.__LINE__ => [
            'args'   => [new \ArrayObject(['o' => 'O'])],
            'expect' => ['o' => 'O'],
        ];
    }

    /**
     * @psalm-param ArrayValuesArgs $args
     * @psalm-param mixed           $expect
     */
    #[DataProvider('provExpectArrayValues')]
    public function testExpectedArrayValues(array $args, mixed $expect): void
    {
        $values = self::arrayValues(...$args);
        self::assertInstanceOf(ExpectedArrayValues::class, $values);
        self::assertSame($expect, (array) $values);
    }
}

// vim: syntax=php sw=4 ts=4 et:
