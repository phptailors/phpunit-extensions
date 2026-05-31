<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\InternalErrorException;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversalInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactory
 * @covers \Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactoryVisitor
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursiveResultFactoryTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsRecursiveResultFactoryInterface(): void
    {
        $visitor = $this->createStub(RecursiveResultFactoryVisitorInterface::class);
        $traversal = $this->createStub(RecursiveTraversalInterface::class);

        $recursiveResultFactory = new RecursiveResultFactory($visitor, $traversal);

        self::assertInstanceOf(RecursiveResultFactoryInterface::class, $recursiveResultFactory);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetResult(): void
    {
        $visitor = $this->createMock(RecursiveResultFactoryVisitorInterface::class);

        $traversal = $this->createMock(RecursiveTraversalInterface::class);

        $recursiveResultFactory = new RecursiveResultFactory($visitor, $traversal);

        $traversal->expects($this->once())
            ->method('walk')
            ->with(['foo' => 'FOO'], $visitor)
        ;

        $visitor->expects($this->once())
            ->method('begin')
            ->with(true, ['in' => 'IN'])
        ;

        $visitor->expects($this->once())
            ->method('end')
        ;

        $visitor->expects($this->once())
            ->method('result')
            ->willReturn(['out' => 'OUT'])
        ;

        $this->assertSame(['out' => 'OUT'], $recursiveResultFactory->getResult(true, ['foo' => 'FOO'], ['in' => 'IN']));
    }

//    public function testGetResultThrowsInternalErrorException(): void
//    {
//        $visitor = $this->createMock(RecursiveResultFactoryVisitorInterface::class);
//
//        $traversal = $this->createMock(RecursiveTraversalInterface::class);
//
//        $recursiveResultFactory = new RecursiveResultFactory(
//            $visitor,
//            $traversal
//        );
//
//        $traversal->expects($this->once())
//            ->method('walk')
//            ->with([], $visitor)
//        ;
//
//        $visitor->expects($this->once())
//            ->method('begin')
//            ->with(false)
//        ;
//
//        $visitor->expects($this->once())
//            ->method('end')
//        ;
//
//        $visitor->expects($this->once())
//            ->method('result')
//            ->willReturn(null)
//        ;
//
//        $this->expectException(InternalErrorException::class);
//        $this->expectExceptionMessageMatches('/^(?:\\$\w+(?:->\w+)*)->result\(\) returned null$/');
//
//        $recursiveResultFactory->getResult(false, [], []);
//    }

    public function testCreate(): void
    {
        $recursiveResultFactory = RecursiveResultFactory::create();

        $this->assertSame(['x' => 'X'], $recursiveResultFactory->getResult(false, ['a' => 'A'], ['x' => 'X']));
    }
}
// vim: syntax=php sw=4 ts=4 et:
