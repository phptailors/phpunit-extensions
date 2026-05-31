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
use Tailors\PHPUnit\ArrayResult\ArrayResultInterface;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\ValueSelector\ValueSelectorInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveConstraint\DummyAbstractRecursiveConstraint
 *
 * @internal This class is not covered by the backward compatibility promise
 */
final class DummyAbstractRecursiveConstraintTest extends TestCase
{
    public function testCreate(): void
    {
        $constraint = DummyAbstractRecursiveConstraint::create(
            $this->createMock(ArrayResultInterface::class),
            $this->createMock(ComparatorInterface::class),
            $this->createMock(ValueSelectorInterface::class),
            $this->createMock(RecursiveUnwrapperInterface::class)
        );

        $this->assertInstanceOf(DummyAbstractRecursiveConstraint::class, $constraint);
    }
}
// vim: syntax=php sw=4 ts=4 et:
