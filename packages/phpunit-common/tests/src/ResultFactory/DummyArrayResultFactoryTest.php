<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ResultFactory;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ArrayResult\DummyArrayResult;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapper;
use Tailors\PHPUnit\RecursiveResultUnwrapper\RecursiveResultUnwrapperVisitor;
use Tailors\PHPUnit\Result\ResultInterface;


/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ResultFactory\DummyArrayResultFactory
 * @covers \Tailors\PHPUnit\ResultFactory\AbstractArrayResultFactory
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type CtorArgs     = list{0?:?non-falsy-string}
 * @psalm-type GetResultArgs = list{0:bool, 1:array|\Traversable}
 *
 */
final class DummyArrayResultFactoryTest extends TestCase
{
    public function testImplementsResultFactoryInterface(): void
    {
        $object = new DummyArrayResultFactory();

        $this->assertInstanceOf(ResultFactoryInterface::class, $object);
    }

    public function testExtendsAbstractArrayResultFactory(): void
    {
        $object = new DummyArrayResultFactory();

        $this->assertInstanceOf(AbstractArrayResultFactory::class, $object);
    }

    /**
     * @psalm-return \Generator<non-falsy-string, array{ctor: CtorArgs, args: GetResultArgs, expect: mixed}>
     */
    public static function provGetResult(): iterable
    {
        $tagk = RecursiveResultUnwrapperVisitor::tag();
        $tagd = (new DummyArrayResult(false))->tag();

        yield 'DummyArrayResultFactoryTest.php:'.__LINE__ => [
            'ctor' => [],
            'args' => [false, []],
            'expect' => [$tagk => $tagd],
        ];

        yield 'DummyArrayResultFactoryTest.php:'.__LINE__ => [
            'ctor' => [],
            'args' => [true, []],
            'expect' => [$tagk => $tagd],
        ];

        yield 'DummyArrayResultFactoryTest.php:'.__LINE__ => [
            'ctor' => [],
            'args' => [false, new \ArrayObject()],
            'expect' => [$tagk => $tagd],
        ];

        yield 'DummyArrayResultFactoryTest.php:'.__LINE__ => [
            'ctor' => [],
            'args' => [true, new \ArrayObject()],
            'expect' => [$tagk => $tagd],
        ];

        yield 'DummyArrayResultFactoryTest.php:'.__LINE__ => [
            'ctor' => [],
            'args' => [false, [
                'foo' => ['bar' => 'FOO.BAR']
            ]],
            'expect' => [
                'foo' => ['bar' => 'FOO.BAR'],
                $tagk => $tagd,
            ],
        ];

        yield 'DummyArrayResultFactoryTest.php:'.__LINE__ => [
            'ctor' => ['TAG'],
            'args' => [false, [
                'foo' => ['bar' => 'FOO.BAR']
            ]],
            'expect' => [
                'foo' => ['bar' => 'FOO.BAR'],
                $tagk => 'TAG',
            ],
        ];
    }

    /**
     * @dataProvider provGetResult
     *
     * @param mixed $expect
     *
     * @psalm-param CtorArgs    $ctor
     * @psalm-param GetResultArgs $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetResult(array $ctor, array $args, $expect): void
    {
        $factory = new DummyArrayResultFactory(...$ctor);

        $input = $args[1];
        $this->assertTrue($factory->supports($input));

        $result = $factory->getResult(...$args);

        $this->assertInstanceOf(ResultInterface::class, $result);
        $this->assertInstanceOf(\Traversable::class, $result);

        $actual = $args[0];
        $unwrapper = RecursiveResultUnwrapper::create($actual);

        $array = $unwrapper->unwrap($result);

        $this->assertSame($expect, $array);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetResultThrowsInvalidArgumentException(): void
    {
        $factory = new DummyArrayResultFactory();

        $this->assertFalse($factory->supports('X'));

        $method = preg_quote(AbstractArrayResultFactory::class.'::getResult()', '/');
        $message = "/^Argument 2 passed to {$method} must be an array or Traversable, string given\\./";

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches($message);

        $factory->getResult(false, 'X');
    }
}
// vim: syntax=php sw=4 ts=4 et:
