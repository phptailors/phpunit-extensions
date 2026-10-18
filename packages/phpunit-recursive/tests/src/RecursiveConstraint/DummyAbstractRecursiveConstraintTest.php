<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveConstraint;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactoryInterface;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapperInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 */
#[CoversClass(DummyAbstractRecursiveConstraint::class)]
#[CoversClass(DummyAbstractRecursiveConstraintTest::class)]
#[Small]
final class DummyAbstractRecursiveConstraintTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCreateWithTraversable(): void
    {
        /** @psalm-var interface-string<\Traversable<array-key,mixed>> */
        $iterable = \Traversable::class;

        $constraint = DummyAbstractRecursiveConstraint::create(
            $this->createStub($iterable),
            $this->createStub(ComparatorInterface::class),
            $this->createStub(RecursiveResultFactoryInterface::class),
            $this->createStub(RecursiveResultUnwrapperInterface::class)
        );

        self::assertInstanceOf(DummyAbstractRecursiveConstraint::class, $constraint);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCreateWithArray(): void
    {
        $constraint = DummyAbstractRecursiveConstraint::create(
            [],
            $this->createStub(ComparatorInterface::class),
            $this->createStub(RecursiveResultFactoryInterface::class),
            $this->createStub(RecursiveResultUnwrapperInterface::class)
        );

        self::assertInstanceOf(DummyAbstractRecursiveConstraint::class, $constraint);
    }
}
// vim: syntax=php sw=4 ts=4 et:
