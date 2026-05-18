<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Recursive;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Selector\ValueSelectorInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
#[CoversClass(DummyRecursiveConstraintSpecialization::class)]
#[Small]
final class RecursiveConstraintSpecializationTraitTest extends TestCase
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

        $constraint = DummyRecursiveConstraintSpecialization::create($expected);

        $this->assertSame([$expected, 1, 1], DummyRecursiveConstraintSpecialization::$validateExpectations);

        $this->assertSame(DummyRecursiveConstraintSpecialization::$makeComparator, $constraint->comparator);
        $this->assertInstanceOf(ValueSelectorInterface::class, $constraint->valueSelector);
        $this->assertInstanceOf(RecursiveUnwrapper::class, $constraint->unwrapper);

        $this->assertSame(DummyRecursiveConstraintSpecialization::$makeSelector, $constraint->valueSelector);
        $this->assertSame($expected, iterator_to_array($constraint->expected));
    }

    //
    //
    // TESTS
    //
    //

    private static function resetDummyConstraintImplementation(): void
    {
        DummyRecursiveConstraintSpecialization::$validateExpectations = null;
        DummyRecursiveConstraintSpecialization::$makeComparator = null;
        DummyRecursiveConstraintSpecialization::$makeSelector = null;
    }
}
// vim: syntax=php sw=4 ts=4 et:
