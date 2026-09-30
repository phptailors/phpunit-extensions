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
use Tailors\PHPUnit\ArraySpec\DummyResultFactoryWrapper;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Comparator\DummyComparator;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactoryInterface;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapper;
use Tailors\PHPUnit\ResultFactory\DummyArrayResultFactory;
use Tailors\PHPUnit\ResultFactory\ResultFactoryInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveConstraint\DummyRecursiveConstraintSpecialization
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
final class DummyRecursiveConstraintSpecializationTest extends TestCase
{
    protected function setUp(): void
    {
        DummyRecursiveConstraintSpecialization::resetStaticProperties();
    }

    protected function tearDown(): void
    {
        DummyRecursiveConstraintSpecialization::resetStaticProperties();
    }

    public function testUsesConstraintSpecializationTrait(): void
    {
        $this->assertArrayHasKey(RecursiveConstraintSpecializationTrait::class, class_uses(DummyRecursiveConstraintSpecialization::class));
    }

    public function testResetStaticProperties(): void
    {
        DummyRecursiveConstraintSpecialization::$defaultComparatorCompare = true;
        DummyRecursiveConstraintSpecialization::$defaultComparatorAdjective = 'funny';
        DummyRecursiveConstraintSpecialization::$defaultResultFactory = $this->createMock(ResultFactoryInterface::class);
        DummyRecursiveConstraintSpecialization::$validateExpectationsLastParams = [['a' => 'A']];
        DummyRecursiveConstraintSpecialization::$makeComparatorLastParams = [];
        DummyRecursiveConstraintSpecialization::$makeComparatorLastReturn = $this->createMock(ComparatorInterface::class);
        DummyRecursiveConstraintSpecialization::$makeExpectationsLastParams = [['b' => 'B']];
        DummyRecursiveConstraintSpecialization::$makeExpectationsLastReturn = new \ArrayObject();

        DummyRecursiveConstraintSpecialization::resetStaticProperties();

        $this->assertSame(false, DummyRecursiveConstraintSpecialization::$defaultComparatorCompare);
        $this->assertSame('dummy', DummyRecursiveConstraintSpecialization::$defaultComparatorAdjective);
        $this->assertSame(null, DummyRecursiveConstraintSpecialization::$defaultResultFactory);
        $this->assertSame(null, DummyRecursiveConstraintSpecialization::$validateExpectationsLastParams);
        $this->assertSame(null, DummyRecursiveConstraintSpecialization::$makeComparatorLastParams);
        $this->assertSame(null, DummyRecursiveConstraintSpecialization::$makeComparatorLastReturn);
        $this->assertSame(null, DummyRecursiveConstraintSpecialization::$makeExpectationsLastParams);
        $this->assertSame(null, DummyRecursiveConstraintSpecialization::$makeExpectationsLastReturn);
    }

    public function testCreate(): void
    {
        $constraint = DummyRecursiveConstraintSpecialization::create(['a' => 'A']);

        $this->assertInstanceOf(DummyRecursiveConstraintSpecialization::class, $constraint);

        //
        // validateExpectations() called
        //
        $this->assertSame([['a' => 'A'], 1, 1], DummyRecursiveConstraintSpecialization::$validateExpectationsLastParams);

        //
        // makeExpectations() called
        //
        $this->assertSame([['a' => 'A']], DummyRecursiveConstraintSpecialization::$makeExpectationsLastParams);

        $expectations = DummyRecursiveConstraintSpecialization::$makeExpectationsLastReturn;
        $defaultResultFactory = DummyRecursiveConstraintSpecialization::$defaultResultFactory;

        $this->assertInstanceOf(DummyResultFactoryWrapper::class, $expectations);
        $this->assertInstanceOf(DummyArrayResultFactory::class, $defaultResultFactory);
        $this->assertSame($defaultResultFactory, $expectations->getResultFactory());
        $this->assertSame(['a' => 'A'], iterator_to_array($expectations));

        //
        // makeComparator() called
        //
        $this->assertSame([], DummyRecursiveConstraintSpecialization::$makeComparatorLastParams);

        $comparator = DummyRecursiveConstraintSpecialization::$makeComparatorLastReturn;

        $this->assertInstanceOf(DummyComparator::class, $comparator);
        $this->assertSame(DummyRecursiveConstraintSpecialization::$defaultComparatorCompare, $comparator->compare(null, null));
        $this->assertSame(DummyRecursiveConstraintSpecialization::$defaultComparatorAdjective, $comparator->adjective());

        // __construct() called

        $this->assertSame($constraint->expected, DummyRecursiveConstraintSpecialization::$makeExpectationsLastReturn);
        $this->assertSame($constraint->comparator, DummyRecursiveConstraintSpecialization::$makeComparatorLastReturn);
        $this->assertInstanceOf(RecursiveResultFactoryInterface::class, $constraint->recursiveResultFactory);
        $this->assertInstanceOf(RecursiveResultUnwrapper::class, $constraint->recursiveResultUnwrapper);
    }
}
