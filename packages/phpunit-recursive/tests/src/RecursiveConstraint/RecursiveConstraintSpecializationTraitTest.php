<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactory;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapper;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveConstraint\RecursiveConstraintSpecializationTrait
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
final class RecursiveConstraintSpecializationTraitTest extends TestCase
{
    protected function setUp(): void
    {
        DummyRecursiveConstraintSpecialization::resetStaticProperties();
    }

    protected function tearDown(): void
    {
        DummyRecursiveConstraintSpecialization::resetStaticProperties();
    }

    /**
     * @psalm-return iterable<string, array{expected: ArrayLike}>
     */
    public static function provCreate(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'expected' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expected' => ['foo' => 'FOO'],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'expected' => new \ArrayObject(['foo' => 'FOO']),
        ];
    }

    /**
     * @psalm-param ArrayLike $expected
     *
     * @dataProvider provCreate
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCreate(iterable $expected): void
    {
        $array = is_array($expected) ? $expected : iterator_to_array($expected);

        $constraint = DummyRecursiveConstraintSpecialization::create($expected);

        // DummyRecursiveConstraintSpecialization::validateExpectations($expected, 1) was called.
        $this->assertSame([$expected, 1, 1], DummyRecursiveConstraintSpecialization::$validateExpectationsLastParams);

        // DummyRecursiveConstraintSpecialization::makeExpectations($expected) was called and the result was passed to constructor
        $this->assertSame([$expected], DummyRecursiveConstraintSpecialization::$makeExpectationsLastParams);
        $this->assertSame(DummyRecursiveConstraintSpecialization::$makeExpectationsLastReturn, $constraint->expected);
        $this->assertSame($array, (array) $constraint->expected);

        // DummyRecursiveConstraintSpecialization::makeComparator() was called and the result was passed to constructor
        $this->assertSame([], DummyRecursiveConstraintSpecialization::$makeComparatorLastParams);
        $this->assertInstanceOf(ComparatorInterface::class, DummyRecursiveConstraintSpecialization::$makeComparatorLastReturn);
        $this->assertSame(DummyRecursiveConstraintSpecialization::$makeComparatorLastReturn, $constraint->comparator);

        // recursiveResultFactory and recursiveResultUnwrapper are set accordingly
        $this->assertInstanceOf(RecursiveResultFactory::class, $constraint->recursiveResultFactory);
        $this->assertInstanceOf(RecursiveResultUnwrapper::class, $constraint->recursiveResultUnwrapper);
    }
}
// vim: syntax=php sw=4 ts=4 et:
