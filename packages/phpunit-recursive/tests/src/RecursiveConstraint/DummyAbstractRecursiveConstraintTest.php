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
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactoryInterface;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapperInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveConstraint\DummyAbstractRecursiveConstraint
 *
 * @internal This class is not covered by the backward compatibility promise
 */
final class DummyAbstractRecursiveConstraintTest extends TestCase
{
    public function testCreateWithTraversable(): void
    {
        $constraint = DummyAbstractRecursiveConstraint::create(
            $this->createMock(\Traversable::class),
            $this->createMock(ComparatorInterface::class),
            $this->createMock(RecursiveResultFactoryInterface::class),
            $this->createMock(RecursiveResultUnwrapperInterface::class)
        );

        $this->assertInstanceOf(DummyAbstractRecursiveConstraint::class, $constraint);
    }

    public function testCreateWithArray(): void
    {
        $constraint = DummyAbstractRecursiveConstraint::create(
            [],
            $this->createMock(ComparatorInterface::class),
            $this->createMock(RecursiveResultFactoryInterface::class),
            $this->createMock(RecursiveResultUnwrapperInterface::class)
        );

        $this->assertInstanceOf(DummyAbstractRecursiveConstraint::class, $constraint);
    }
}
// vim: syntax=php sw=4 ts=4 et:
