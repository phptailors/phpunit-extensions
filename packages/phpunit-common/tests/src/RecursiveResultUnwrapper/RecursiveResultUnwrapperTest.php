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
use Tailors\PHPUnit\InternalErrorException;
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
        $recursiveResultUnwrapperVisitor = $this->createStub(RecursiveResultUnwrapperVisitorInterface::class);
        $recursiveTraversal = $this->createStub(RecursiveTraversalInterface::class);

        $recursiveResultUnwrapper = new RecursiveResultUnwrapper(
            $recursiveResultUnwrapperVisitor,
            $recursiveTraversal
        );

        self::assertInstanceOf(RecursiveResultUnwrapperInterface::class, $recursiveResultUnwrapper);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testUnwrap(): void
    {
        $recursiveResultUnwrapperVisitor = $this->createMock(RecursiveResultUnwrapperVisitorInterface::class);

        $recursiveTraversal = $this->createMock(RecursiveTraversalInterface::class);

        $recursiveResultUnwrapper = new RecursiveResultUnwrapper(
            $recursiveResultUnwrapperVisitor,
            $recursiveTraversal
        );

        $recursiveTraversal->expects($this->once())
            ->method('walk')
            ->with(['in' => 'IN'], $recursiveResultUnwrapperVisitor)
        ;

        $recursiveResultUnwrapperVisitor->expects($this->once())
            ->method('begin')
            ->with(true);

        $recursiveResultUnwrapperVisitor->expects($this->once())
            ->method('end');

        $recursiveResultUnwrapperVisitor->expects($this->once())
            ->method('result')
            ->willReturn(['out' => 'OUT']);

        $this->assertSame(['out' => 'OUT'], $recursiveResultUnwrapper->unwrap(true, ['in' => 'IN']));
    }

    public function testUnwrapThrowsInternalErrorException(): void
    {
        $recursiveResultUnwrapperVisitor = $this->createMock(RecursiveResultUnwrapperVisitorInterface::class);

        $recursiveTraversal = $this->createMock(RecursiveTraversalInterface::class);

        $recursiveResultUnwrapper = new RecursiveResultUnwrapper(
            $recursiveResultUnwrapperVisitor,
            $recursiveTraversal
        );

        $recursiveTraversal->expects($this->once())
            ->method('walk')
            ->with([], $recursiveResultUnwrapperVisitor)
        ;

        $recursiveResultUnwrapperVisitor->expects($this->once())
            ->method('begin')
            ->with(false);

        $recursiveResultUnwrapperVisitor->expects($this->once())
            ->method('end');

        $recursiveResultUnwrapperVisitor->expects($this->once())
            ->method('result')
            ->willReturn(null);

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessageMatches('/^(?:\\$\w+(?:->\w+)*)->result\(\) returned null$/');

        $recursiveResultUnwrapper->unwrap(false, []);
    }

    public function testCreate(): void
    {
        $recursiveResultUnwrapper = RecursiveResultUnwrapper::create();

        $this->assertSame(['x' => 'X'], $recursiveResultUnwrapper->unwrap(false, ['x' => 'X']));
    }
}
// vim: syntax=php sw=4 ts=4 et:
