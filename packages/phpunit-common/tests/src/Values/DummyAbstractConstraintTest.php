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
use Tailors\PHPUnit\Comparator\ComparatorInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Values\DummyAbstractConstraint
 *
 * @internal This class is not covered by the backward compatibility promise
 */
final class DummyAbstractConstraintTest extends TestCase
{
    public function testCreate(): void
    {
        $constraint = DummyAbstractConstraint::create(
            $this->createMock(ValuesInterface::class),
            $this->createMock(ComparatorInterface::class),
            $this->createMock(ValueSelectorInterface::class),
            $this->createMock(RecursiveUnwrapperInterface::class)
        );

        $this->assertInstanceOf(DummyAbstractConstraint::class, $constraint);
    }
}
// vim: syntax=php sw=4 ts=4 et:
