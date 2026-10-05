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
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\ResultFactory\AbstractArrayResultFactory
 * @covers \Tailors\PHPUnit\ResultFactory\DummyArrayResultFactory
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from \Tailors\PHPUnit\Common\TypesInterface
 * @psalm-type CtorArgs      = list{0?:?non-falsy-string,1?:?\Closure(iterable<array-key,mixed>):array}
 * @psalm-type GetResultArgs = list{0:bool, 1:ArrayLike}
 */
final class DummyArrayResultFactoryTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultFactoryInterface(): void
    {
        $object = new DummyArrayResultFactory();

        $this->assertInstanceOf(ResultFactoryInterface::class, $object);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractArrayResultFactory(): void
    {
        $object = new DummyArrayResultFactory();

        $this->assertInstanceOf(AbstractArrayResultFactory::class, $object);
    }

    /**
     * @psalm-return iterable<string, array{ctor: CtorArgs, args: GetResultArgs, expect: mixed}>
     */
    public static function provGetResult(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'args'   => [false, []],
            'expect' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'args'   => [true, []],
            'expect' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'args'   => [false, new \ArrayObject()],
            'expect' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'args'   => [true, new \ArrayObject()],
            'expect' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [],
            'args'   => [false, [
                'foo' => ['bar' => 'FOO.BAR'],
            ]],
            'expect' => [
                'foo' => ['bar' => 'FOO.BAR'],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => ['TAG'],
            'args'   => [false, [
                'foo' => ['bar' => 'FOO.BAR'],
            ]],
            'expect' => [
                'foo' => ['bar' => 'FOO.BAR'],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctor'   => [
                null,
                function (iterable $input): array {
                    $array = is_array($input) ? $input : iterator_to_array($input);
                    ksort($array);

                    return $array;
                },
            ],
            'args'   => [false, [
                'foo' => 'FOO',
                'bar' => 'BAR',
            ]],
            'expect' => [
                'bar' => 'BAR',
                'foo' => 'FOO',
            ],
        ];
    }

    /**
     * @dataProvider provGetResult
     *
     * @param mixed $expect
     *
     * @psalm-param CtorArgs      $ctor
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
        $this->assertInstanceOf(DummyArrayResult::class, $result);

        $actual = $args[0];

        $this->assertSame($actual, $result->actual());
        $this->assertSame($expect, iterator_to_array($result));

        $tag = $ctor[0] ?? DummyArrayResult::class.':a1a44e79c791a1fe22ac49067eef00b222d10131';
        $this->assertSame($tag, $result->tag());
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
