<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveMapper;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\InternalErrorException;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversalInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveMapper\RecursiveMapper
 * @covers \Tailors\PHPUnit\RecursiveMapper\RecursiveMapperTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class RecursiveMapperTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsRecursiveMapperInterface(): void
    {
        $recursiveMapperVisitor = $this->createStub(RecursiveMapperVisitorInterface::class);
        $recursiveTraversal = $this->createStub(RecursiveTraversalInterface::class);

        $recursiveMapper = new RecursiveMapper($recursiveMapperVisitor, $recursiveTraversal);

        self::assertInstanceOf(RecursiveMapperInterface::class, $recursiveMapper);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMapWithoutMappers(): void
    {
        $recursiveMapperVisitor = $this->createMock(RecursiveMapperVisitorInterface::class);
        $recursiveTraversal = $this->createMock(RecursiveTraversalInterface::class);

        $recursiveMapper = new RecursiveMapper($recursiveMapperVisitor, $recursiveTraversal);

        $recursiveTraversal->expects($this->once())
            ->method('walk')
            ->with(['in' => 'IN'], $recursiveMapperVisitor)
        ;

        $recursiveMapperVisitor->expects($this->once())
            ->method('begin')
            ->with(null, null)
        ;

        $recursiveMapperVisitor->expects($this->once())
            ->method('end')
        ;

        $recursiveMapperVisitor->expects($this->once())
            ->method('result')
            ->willReturn(['out' => 'OUT'])
        ;

        self::assertSame(['out' => 'OUT'], $recursiveMapper->map(['in' => 'IN']));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMapWithMappers(): void
    {
        $recursiveMapperVisitor = $this->createMock(RecursiveMapperVisitorInterface::class);
        $recursiveTraversal = $this->createMock(RecursiveTraversalInterface::class);

        $recursiveMapper = new RecursiveMapper($recursiveMapperVisitor, $recursiveTraversal);

        $arrayMapper =
            function (): bool {
                // @codeCoverageIgnoreStart
                return true;
                // @codeCoverageIgnoreEnd
            };

        $valueMapper =
            /**
             * @param mixed $value
             *
             * @return mixed
             */
            function ($value) {
                // @codeCoverageIgnoreStart
                return $value;
                // @codeCoverageIgnoreEnd
            };

        $recursiveTraversal->expects($this->once())
            ->method('walk')
            ->with(['in' => 'IN'], $recursiveMapperVisitor)
        ;

        $recursiveMapperVisitor->expects($this->once())
            ->method('begin')
            ->with($arrayMapper, $valueMapper)
        ;

        $recursiveMapperVisitor->expects($this->once())
            ->method('end')
        ;

        $recursiveMapperVisitor->expects($this->once())
            ->method('result')
            ->willReturn(['out' => 'OUT'])
        ;

        self::assertSame(['out' => 'OUT'], $recursiveMapper->map(['in' => 'IN'], $arrayMapper, $valueMapper));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testMapThrowsInternalErrorException(): void
    {
        $recursiveMapperVisitor = $this->createMock(RecursiveMapperVisitorInterface::class);
        $recursiveTraversal = $this->createMock(RecursiveTraversalInterface::class);

        $recursiveMapper = new RecursiveMapper($recursiveMapperVisitor, $recursiveTraversal);

        $recursiveTraversal->expects($this->once())
            ->method('walk')
            ->with([], $recursiveMapperVisitor)
        ;

        $recursiveMapperVisitor->expects($this->once())
            ->method('begin')
            ->with(null, null)
        ;

        $recursiveMapperVisitor->expects($this->once())
            ->method('end')
        ;

        $recursiveMapperVisitor->expects($this->once())
            ->method('result')
            ->willReturn(null)
        ;

        $this->expectException(InternalErrorException::class);
        $this->expectExceptionMessageMatches('/^(?:\\$\w+(?:->\w+)*)->result\(\) returned null$/');

        $recursiveMapper->map([]);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCreate(): void
    {
        $recursiveMapper = RecursiveMapper::create();

        self::assertSame(['x' => 'X'], $recursiveMapper->map(['x' => 'X']));
    }
}
// vim: syntax=php sw=4 ts=4 et:
