<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\ConstraintImplementationTrait
 * @covers \Tailors\PHPUnit\Values\DummyConstraintImplementation
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class ConstraintImplementationTraitTest extends TestCase
{
    protected function setUp(): void
    {
        self::resetDummyConstraintImplementation();
    }

    protected function tearDown(): void
    {
        self::resetDummyConstraintImplementation();
    }

    public function testCreate(): void
    {
        $expected = ['foo' => 'FOO'];

        $constraint = DummyConstraintImplementation::create($expected);

        $this->assertSame([$expected, 1, 1], DummyConstraintImplementation::$validateExpectations);

        $this->assertSame(DummyConstraintImplementation::$makeComparator, $constraint->comparator);
        $this->assertInstanceOf(ExpectedValuesSelection::class, $constraint->selection);
        $this->assertInstanceOf(RecursiveUnwrapper::class, $constraint->unwrapper);

        $this->assertSame(DummyConstraintImplementation::$makeSelector, $constraint->selection->getSelector());
        $this->assertSame($expected, $constraint->selection->getArrayCopy());
    }

    //
    //
    // TESTS
    //
    //

    private static function resetDummyConstraintImplementation(): void
    {
        DummyConstraintImplementation::$validateExpectations = null;
        DummyConstraintImplementation::$makeComparator = null;
        DummyConstraintImplementation::$makeSelector = null;
    }
}
// vim: syntax=php sw=4 ts=4 et:
