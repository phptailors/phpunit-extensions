<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultUnwrapper;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversalInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapper
 * @covers \Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapperVisitor
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveResultUnwrapperTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsRecursiveResultUnwrapperInterface(): void
    {
        $expectedResultUnwrapperVisitor = $this->createStub(RecursiveResultUnwrapperVisitorInterface::class);
        $actualResultUnwrapperVisitor = $this->createStub(RecursiveResultUnwrapperVisitorInterface::class);
        $recursiveTraversal = $this->createStub(RecursiveTraversalInterface::class);

        $recursiveResultUnwrapper = new RecursiveResultUnwrapper(
            $expectedResultUnwrapperVisitor,
            $actualResultUnwrapperVisitor,
            $recursiveTraversal
        );

        self::assertInstanceOf(RecursiveResultUnwrapperInterface::class, $recursiveResultUnwrapper);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testUnwrapExpectedResult(): void
    {
        $expectedResultUnwrapperVisitor = $this->createMock(RecursiveResultUnwrapperVisitorInterface::class);
        $actualResultUnwrapperVisitor = $this->createMock(RecursiveResultUnwrapperVisitorInterface::class);

        $recursiveTraversal = $this->createMock(RecursiveTraversalInterface::class);

        $recursiveResultUnwrapper = new RecursiveResultUnwrapper(
            $expectedResultUnwrapperVisitor,
            $actualResultUnwrapperVisitor,
            $recursiveTraversal
        );

        $expectedResultUnwrapperVisitor->expects($this->once())
            ->method('reset');

        $recursiveTraversal->expects($this->once())
            ->method('walk')
            ->with(['in' => 'IN'], $expectedResultUnwrapperVisitor)
        ;

        $expectedResultUnwrapperVisitor->expects($this->once())
            ->method('result')
            ->willReturn(['out' => 'OUT']);

        $actualResultUnwrapperVisitor->expects($this->never())->method('reset');
        $actualResultUnwrapperVisitor->expects($this->never())->method('result');

        $this->assertSame(['out' => 'OUT'], $recursiveResultUnwrapper->unwrapExpectedResult(['in' => 'IN']));
    }
}
// vim: syntax=php sw=4 ts=4 et:
