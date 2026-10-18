<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\ResultFactory;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\InvalidArgumentException;
use Tailors\PHPUnit\Result\DummyArrayResult;
use Tailors\PHPUnit\Result\ResultInterface;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type GetResultArgs = list{0:bool, 1:ArrayLike}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
#[CoversClass(AbstractArrayResultFactory::class)]
#[CoversClass(AbstractArrayResultFactoryTest::class)]
#[Small]
final class AbstractArrayResultFactoryTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultFactoryInterface(): void
    {
        $object = $this->createStub(AbstractArrayResultFactory::class);

        $this->assertInstanceOf(ResultFactoryInterface::class, $object);
    }

    /**
     * @psalm-return iterable<string, array{input: mixed, expect: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provSupports(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'input'  => null,
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'input'  => true,
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'input'  => 123,
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'input'  => 123.5,
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'input'  => 'a string',
            'expect' => false,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'input'  => [],
            'expect' => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'input'  => new \ArrayObject(),
            'expect' => true,
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'input'  => new \EmptyIterator(),
            'expect' => true,
        ];
    }

    /**
     * @param mixed $input
     * @param mixed $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provSupports')]
    public function testSupports($input, $expect): void
    {
        $factory = $this->createStub(AbstractArrayResultFactory::class);

        $this->assertSame($expect, $factory->supports($input));
    }

    /**
     * @psalm-return iterable<string, array{args: GetResultArgs, expect: mixed}>
     *
     * @codeCoverageIgnore
     */
    public static function provGetResult(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'args'   => [false, []],
            'expect' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'args'   => [true, []],
            'expect' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'args'   => [false, new \ArrayObject()],
            'expect' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'args'   => [true, new \ArrayObject()],
            'expect' => [],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'args'   => [false, [
                'foo' => ['bar' => 'FOO.BAR'],
            ]],
            'expect' => [
                'foo' => ['bar' => 'FOO.BAR'],
            ],
        ];
    }

    /**
     * @param mixed $expect
     *
     * @psalm-param GetResultArgs $args
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    #[DataProvider('provGetResult')]
    public function testGetResult(array $args, $expect): void
    {
        $factory = self::getObject();

        $input = $args[1];
        $this->assertTrue($factory->supports($input));

        $result = $factory->getResult(...$args);

        $this->assertInstanceOf(\Traversable::class, $result);
        $this->assertInstanceOf(ResultInterface::class, $result);

        $actual = $args[0];

        $this->assertSame($actual, $result->actual());
        $this->assertSame($expect, iterator_to_array($result));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testGetResultThrowsInvalidArgumentException(): void
    {
        $factory = self::getObject();

        $this->assertFalse($factory->supports('X'));

        $method = preg_quote(AbstractArrayResultFactory::class.'::getResult()', '/');
        $message = "/^Argument 2 passed to {$method} must be an array or Traversable, string given\\./";

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches($message);

        $factory->getResult(false, 'X');
    }

    private static function getObject(): AbstractArrayResultFactory
    {
        return new class() extends AbstractArrayResultFactory {
            /**
             * @psalm-param ArrayLike $input
             *
             * @psalm-return ResultInterface&\Traversable
             */
            protected function getArrayResult(bool $actual, iterable $input): ResultInterface
            {
                return new DummyArrayResult($actual, $input);
            }
        };
    }
}
// vim: syntax=php sw=4 ts=4 et:
