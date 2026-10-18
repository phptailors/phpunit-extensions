<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\RecursiveResultFactory;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ArraySpec\DummyResultFactoryAndValueSelectorWrapper;
use Tailors\PHPUnit\ArraySpec\DummyResultFactoryWrapper;
use Tailors\PHPUnit\ArraySpec\DummyValueSelectorWrapper;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\RecursiveTraversal\RecursiveTraversalInterface;
use Tailors\PHPUnit\ResultFactory\DummyResultFactory;
use Tailors\PHPUnit\ValueSelector\DummyValueSelector;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
#[CoversClass(RecursiveResultFactory::class)]
#[CoversClass(RecursiveResultFactoryTest::class)]
#[Small]
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
        $expectedResultVisitor = $this->createMock(RecursiveExpectedResultFactoryVisitorInterface::class);
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

        self::assertSame(['out' => 'OUT'], $recursiveResultFactory->getExpectedResult(['foo' => 'FOO']));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetActualResult(): void
    {
        $expectedResultVisitor = $this->createStub(RecursiveExpectedResultFactoryVisitorInterface::class);
        $actualResultVisitor = $this->createMock(RecursiveActualResultFactoryVisitorInterface::class);
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

        self::assertSame(['out' => 'OUT'], $recursiveResultFactory->getActualResult(['foo' => 'FOO'], ['in' => 'IN']));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCreate(): void
    {
        $recursiveResultFactory = RecursiveResultFactory::create();

        self::assertSame(['a' => 'A'], $recursiveResultFactory->getExpectedResult(['a' => 'A']));
        self::assertSame(['x' => 'X'], $recursiveResultFactory->getActualResult(['a' => 'A'], ['x' => 'X']));
    }

    /**
     * @psalm-return iterable<string, array{
     *      array: ArrayLike,
     *      input: mixed,
     *      expect: mixed
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provSupports(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => [],
            'input'  => null,
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => [],
            'input'  => '',
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => [],
            'input'  => new \ArrayObject(),
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => [],
            'input'  => [],
            'expect' => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new DummyResultFactoryAndValueSelectorWrapper(
                new DummyResultFactory(true),
                new DummyValueSelector(false, false, '', ''),
                [],
            ),
            'input'  => [],
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new DummyResultFactoryAndValueSelectorWrapper(
                new DummyResultFactory(false),
                new DummyValueSelector(true, false, '', ''),
                [],
            ),
            'input'  => [],
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new DummyResultFactoryAndValueSelectorWrapper(
                new DummyResultFactory(true),
                new DummyValueSelector(true, false, '', ''),
                [],
            ),
            'input'  => [],
            'expect' => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new DummyValueSelectorWrapper(
                new DummyValueSelector(true, false, '', ''),
                [],
            ),
            'input'  => [],
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new DummyResultFactoryWrapper(
                new DummyResultFactory(true),
                [],
            ),
            'input'  => [],
            'expect' => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new DummyResultFactoryWrapper(
                new DummyResultFactory(false),
                [],
            ),
            'input'  => [],
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new DummyResultFactoryAndValueSelectorWrapper(
                new DummyResultFactory(fn ($input): bool => is_array($input)),
                new DummyValueSelector(fn ($subject): bool => is_array($subject), false, '', ''),
                [],
            ),
            'input'  => [],
            'expect' => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new DummyResultFactoryAndValueSelectorWrapper(
                new DummyResultFactory(fn ($input): bool => is_array($input)),
                new DummyValueSelector(true),
                [],
            ),
            'input'  => [],
            'expect' => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new DummyResultFactoryAndValueSelectorWrapper(
                new DummyResultFactory(fn ($input): bool => [] === $input),
                new DummyValueSelector(fn ($subject): bool => is_iterable($subject)),
                [],
            ),
            'input'  => new \ArrayObject([]),
            'expect' => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new DummyResultFactoryAndValueSelectorWrapper(
                new DummyResultFactory(true),
                new DummyValueSelector(fn ($subject): bool => is_array($subject), false, '', ''),
                [],
            ),
            'input'  => [],
            'expect' => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new DummyResultFactoryAndValueSelectorWrapper(
                new DummyResultFactory(true),
                new DummyValueSelector(fn ($subject): bool => is_array($subject), false, '', ''),
                [],
            ),
            'input'  => new \ArrayObject([]),
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new DummyResultFactoryWrapper(
                new DummyResultFactory(fn ($input): bool => is_array($input)),
                [],
            ),
            'input'  => [],
            'expect' => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'array'  => new DummyResultFactoryWrapper(
                new DummyResultFactory(fn ($input): bool => is_array($input)),
                [],
            ),
            'input'  => new \ArrayObject([]),
            'expect' => false,
        ];
    }

    /**
     * @param mixed $input
     * @param mixed $expect
     *
     * @psalm-param ArrayLike $array
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provSupports')]
    public function testSupports(iterable $array, $input, $expect): void
    {
        $expectedResultVisitor = $this->createStub(RecursiveExpectedResultFactoryVisitorInterface::class);
        $actualResultVisitor = $this->createStub(RecursiveActualResultFactoryVisitorInterface::class);
        $traversal = $this->createStub(RecursiveTraversalInterface::class);

        $recursiveResultFactory = new RecursiveResultFactory($expectedResultVisitor, $actualResultVisitor, $traversal);

        self::assertSame($expect, $recursiveResultFactory->supports($array, $input));
    }
}
// vim: syntax=php sw=4 ts=4 et:
