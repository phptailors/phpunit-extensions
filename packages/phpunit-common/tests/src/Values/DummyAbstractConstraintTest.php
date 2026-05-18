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
use Tailors\PHPUnit\Comparator\ComparatorInterface;
use Tailors\PHPUnit\Selector\ValueSelectorInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 */
#[CoversClass(DummyAbstractConstraint::class)]
#[Small]
final class DummyAbstractConstraintTest extends TestCase
{
    public function testCreate(): void
    {
        $constraint = DummyAbstractConstraint::create(
            $this->createStub(ValuesInterface::class),
            $this->createStub(ComparatorInterface::class),
            $this->createStub(ValueSelectorInterface::class),
            $this->createStub(RecursiveUnwrapperInterface::class)
        );

        $this->assertInstanceOf(DummyAbstractConstraint::class, $constraint);
    }
}
// vim: syntax=php sw=4 ts=4 et:
