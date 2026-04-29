<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(ConstraintImplementationTrait::class)]
#[CoversClass(DummyConstraintImplementation::class)]
#[Small]
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
        $this->assertInstanceOf(ValueSelectorInterface::class, $constraint->valueSelector);
        $this->assertInstanceOf(RecursiveUnwrapper::class, $constraint->unwrapper);

        $this->assertSame(DummyConstraintImplementation::$makeSelector, $constraint->valueSelector);
        $this->assertSame($expected, $constraint->expected->getArrayCopy());
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
