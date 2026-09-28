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
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversalInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\RecursiveResultFactory\RecursiveResultFactory
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArrayLike = iterable<array-key, mixed>
 */
final class RecursiveResultFactoryTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsRecursiveResultFactoryInterface(): void
    {
        $expectedResultVisitor = $this->createStub(RecursiveExpectedResultFactoryVisitorInterface::class);
        $actualResultVisitor = $this->createStub(RecursiveActualResultFactoryVisitorInterface::class);
        $traversal = $this->createStub(RecursiveTraversalInterface::class);

        $recursiveResultFactory = new RecursiveResultFactory($expectedResultVisitor, $actualResultVisitor, $traversal);

        self::assertInstanceOf(RecursiveResultFactoryInterface::class, $recursiveResultFactory);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetExpectedResult(): void
    {
        $expectedResultVisitor = $this->createStub(RecursiveExpectedResultFactoryVisitorInterface::class);
        $actualResultVisitor = $this->createStub(RecursiveActualResultFactoryVisitorInterface::class);
        $traversal = $this->createMock(RecursiveTraversalInterface::class);

        $recursiveResultFactory = new RecursiveResultFactory($expectedResultVisitor, $actualResultVisitor, $traversal);

        $traversal->expects($this->once())
            ->method('walk')
            ->with(['foo' => 'FOO'], $expectedResultVisitor)
        ;

        $expectedResultVisitor->expects($this->once())
            ->method('begin')
            ->with()
        ;

        $expectedResultVisitor->expects($this->once())
            ->method('end')
        ;

        $expectedResultVisitor->expects($this->once())
            ->method('result')
            ->willReturn(['out' => 'OUT'])
        ;

        $this->assertSame(['out' => 'OUT'], $recursiveResultFactory->getExpectedResult(['foo' => 'FOO'], ['in' => 'IN']));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetActualResult(): void
    {
        $expectedResultVisitor = $this->createStub(RecursiveExpectedResultFactoryVisitorInterface::class);
        $actualResultVisitor = $this->createStub(RecursiveActualResultFactoryVisitorInterface::class);
        $traversal = $this->createMock(RecursiveTraversalInterface::class);

        $recursiveResultFactory = new RecursiveResultFactory($expectedResultVisitor, $actualResultVisitor, $traversal);

        $traversal->expects($this->once())
            ->method('walk')
            ->with(['foo' => 'FOO'], $actualResultVisitor)
        ;

        $actualResultVisitor->expects($this->once())
            ->method('begin')
            ->with(['in' => 'IN'])
        ;

        $actualResultVisitor->expects($this->once())
            ->method('end')
        ;

        $actualResultVisitor->expects($this->once())
            ->method('result')
            ->willReturn(['out' => 'OUT'])
        ;

        $this->assertSame(['out' => 'OUT'], $recursiveResultFactory->getActualResult(['foo' => 'FOO'], ['in' => 'IN']));
    }

    public function testCreate(): void
    {
        $recursiveResultFactory = RecursiveResultFactory::create();

        $this->assertSame(['a' => 'A'], $recursiveResultFactory->getExpectedResult(['a' => 'A'], ['x' => 'X']));
        $this->assertSame(['x' => 'X'], $recursiveResultFactory->getActualResult(['a' => 'A'], ['x' => 'X']));
    }

    /**
     * @psalm-return iterable<string, array{
     *      array: ArrayLike,
     *      input: mixed,
     *      expect: mixed
     * }>
     */
    public static function provSupports(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'array' => [],
            'input' => null,
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array' => [],
            'input' => '',
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array' => [],
            'input' => new \ArrayObject(),
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array' => [],
            'input' => [],
            'expect' => true,
        ];
    }

    /**
     * @dataProvider provSupports
     *
     * @param mixed $input
     * @param mixed $expect
     *
     * @psalm-param ArrayLike $array
     */
    public function testSupports(iterable $array, $input, $expect): void
    {
        $expectedResultVisitor = $this->createStub(RecursiveExpectedResultFactoryVisitorInterface::class);
        $actualResultVisitor = $this->createStub(RecursiveActualResultFactoryVisitorInterface::class);
        $traversal = $this->createStub(RecursiveTraversalInterface::class);

        $recursiveResultFactory = new RecursiveResultFactory($expectedResultVisitor, $actualResultVisitor, $traversal);

        $this->assertSame($expect, $recursiveResultFactory->supports($array, $input));
    }
}
// vim: syntax=php sw=4 ts=4 et:
