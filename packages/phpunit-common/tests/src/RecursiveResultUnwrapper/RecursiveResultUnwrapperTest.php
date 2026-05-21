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
        $traversal = $this->createStub(RecursiveTraversalInterface::class);
        $visitor = $this->createStub(RecursiveResultUnwrapperVisitorInterface::class);

        self::assertInstanceOf(RecursiveResultUnwrapperInterface::class, new RecursiveResultUnwrapper($visitor, $traversal));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testUnwrap(): void
    {
        $traversal = $this->createMock(RecursiveTraversalInterface::class);
        $visitor = $this->createMock(RecursiveResultUnwrapperVisitorInterface::class);

        $unwrapper = new RecursiveResultUnwrapper($visitor, $traversal);

        $visitor->expects($this->once())
            ->method('reset');

        $traversal->expects($this->once())
            ->method('walk')
            ->with(['in' => 'IN'], $visitor)
        ;

        $visitor->expects($this->once())
            ->method('result')
            ->willReturn(['out' => 'OUT']);

        $this->assertSame(['out' => 'OUT'], $unwrapper->unwrap(['in' => 'IN']));
    }
}
// vim: syntax=php sw=4 ts=4 et:
