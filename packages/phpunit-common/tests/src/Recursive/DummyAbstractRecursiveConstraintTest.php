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
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Selector\ValueSelectorInterface;
use Tailors\PHPUnit\Values\ValuesInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 */
#[CoversClass(DummyAbstractRecursiveConstraint::class)]
#[Small]
final class DummyAbstractRecursiveConstraintTest extends TestCase
{
    public function testCreate(): void
    {
        $constraint = DummyAbstractRecursiveConstraint::create(
            $this->createMock(ValuesInterface::class),
            $this->createMock(ComparatorInterface::class),
            $this->createMock(ValueSelectorInterface::class),
            $this->createMock(RecursiveUnwrapperInterface::class)
        );

        $this->assertInstanceOf(DummyAbstractRecursiveConstraint::class, $constraint);
    }
}
// vim: syntax=php sw=4 ts=4 et:
