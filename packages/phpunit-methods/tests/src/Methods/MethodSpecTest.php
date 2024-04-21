<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Methods;

use PHPUnit\Framework\TestCase;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Methods\MethodSpec
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 */
final class MethodSpecTest extends TestCase
{
    public const IS_STATIC = MethodSpec::IS_STATIC;
    public const IS_PUBLIC = MethodSpec::IS_PUBLIC;
    public const IS_PROTECTED = MethodSpec::IS_PROTECTED;
    public const IS_PRIVATE = MethodSpec::IS_PRIVATE;
    public const IS_ABSTRACT = MethodSpec::IS_ABSTRACT;
    public const IS_FINAL = MethodSpec::IS_FINAL;

    public const MMASK =
        self::IS_STATIC
        | self::IS_PUBLIC
        | self::IS_PROTECTED
        | self::IS_PRIVATE
        | self::IS_ABSTRACT
        | self::IS_FINAL;

    public const VMASK =
        self::IS_PUBLIC
        | self::IS_PROTECTED
        | self::IS_PRIVATE;

    /**
     * @psalm-return iterable<array-key,array{
     *  0: array{0:non-empty-string, 1?:?bool, 2?:?int, 3?:?bool, 4?:?bool},
     *  1: array{name:mixed, static:mixed, access:mixed, abstract:mixed, final:mixed}
     * }>
     */
    public static function provConstructor(): iterable
    {
        for ($n = 0; $n <= 4; ++$n) {
            $args = array_fill(0, $n, null);

            yield [
                ['foo', ...$args],
                [
                    'name'     => 'foo',
                    'static'   => null,
                    'access'   => null,
                    'abstract' => null,
                    'final'    => null,
                ],
            ];
        }

        yield [
            ['foo', true],
            [
                'name'     => 'foo',
                'static'   => true,
                'access'   => null,
                'abstract' => null,
                'final'    => null,
            ],
        ];

        yield [
            ['foo', false],
            [
                'name'     => 'foo',
                'static'   => false,
                'access'   => null,
                'abstract' => null,
                'final'    => null,
            ],
        ];

        yield [
            ['foo', null, self::IS_PUBLIC],
            [
                'name'     => 'foo',
                'static'   => null,
                'access'   => self::IS_PUBLIC,
                'abstract' => null,
                'final'    => null,
            ],
        ];

        yield [
            ['foo', null, self::IS_PROTECTED],
            [
                'name'     => 'foo',
                'static'   => null,
                'access'   => self::IS_PROTECTED,
                'abstract' => null,
                'final'    => null,
            ],
        ];

        yield [
            ['foo', null, self::IS_PRIVATE],
            [
                'name'     => 'foo',
                'static'   => null,
                'access'   => self::IS_PRIVATE,
                'abstract' => null,
                'final'    => null,
            ],
        ];

        yield [
            ['foo', null, self::IS_PUBLIC | self::IS_PROTECTED],
            [
                'name'     => 'foo',
                'static'   => null,
                'access'   => self::IS_PUBLIC | self::IS_PROTECTED,
                'abstract' => null,
                'final'    => null,
            ],
        ];

        yield [
            ['foo', null, null, false],
            [
                'name'     => 'foo',
                'static'   => null,
                'access'   => null,
                'abstract' => false,
                'final'    => null,
            ],
        ];

        yield [
            ['foo', null, null, true],
            [
                'name'     => 'foo',
                'static'   => null,
                'access'   => null,
                'abstract' => true,
                'final'    => null,
            ],
        ];

        yield [
            ['foo', null, null, null, false],
            [
                'name'     => 'foo',
                'static'   => null,
                'access'   => null,
                'abstract' => null,
                'final'    => false,
            ],
        ];

        yield [
            ['foo', null, null, null, true],
            [
                'name'     => 'foo',
                'static'   => null,
                'access'   => null,
                'abstract' => null,
                'final'    => true,
            ],
        ];
    }

    /**
     * @dataProvider provConstructor
     */
    public function testConstructor(array $args, array $expect): void
    {
        $spec = new MethodSpec(...$args);
        $this->assertSame($expect['name'], $spec->getName());
        $this->assertSame($expect['static'], $spec->getStatic());
        $this->assertSame($expect['access'], $spec->getAccess());
        $this->assertSame($expect['abstract'], $spec->getAbstract());
        $this->assertSame($expect['final'], $spec->getFinal());
    }

    /**
     * @psalm-return iterable<array-key,array{
     *  0: array{0:non-empty-string, 1?:?bool, 2?:?int, 3?:?bool, 4?:?bool},
     *  1: \Closure(TestCase):\ReflectionMethod,
     *  2: bool
     * }>
     */
    public static function provMatches(): iterable
    {
        // just name
        yield [['bar'], fn (TestCase $test) => self::makeMethod($test, 'foo'), false];

        $cases1 = [
            [],
            [self::IS_PUBLIC],
            [self::IS_PROTECTED],
            [self::IS_PRIVATE],
            [self::IS_STATIC],
            [self::IS_ABSTRACT],
            [self::IS_FINAL],
            [self::IS_STATIC | self::IS_PROTECTED],
            [self::IS_ABSTRACT | self::IS_PROTECTED],
            [self::IS_STATIC | self::IS_ABSTRACT],
            [self::IS_STATIC | self::IS_FINAL],
        ];

        foreach ($cases1 as $modifiers) {
            yield [['foo'], fn (TestCase $test) => self::makeMethod($test, 'foo', ...$modifiers), true];
        }

        // Test single boolean modifier (static, abstract, final)
        $cases2 = [
            [0, self::IS_STATIC],
            [2, self::IS_ABSTRACT],
            [3, self::IS_FINAL],
        ];

        foreach ($cases2 as $case) {
            [$n, $modifier] = $case;
            $args = array_fill(0, $n, null);

            $args[$n] = true;

            yield [
                ['foo', ...$args],
                fn (TestCase $test) => self::makeMethod($test, 'foo', $modifier),
                true,
            ];

            yield [
                ['foo', ...$args],
                fn (TestCase $test) => self::makeMethod($test, 'foo', self::MMASK & ~$modifier),
                false,
            ];

            $args[$n] = false;

            yield [
                ['foo', ...$args],
                fn (TestCase $test) => self::makeMethod($test, 'foo', $modifier),
                false,
            ];

            yield [
                ['foo', ...$args],
                fn (TestCase $test) => self::makeMethod($test, 'foo', self::MMASK & ~$modifier),
                true,
            ];
        }

        // Test visibility modifier
        $cases3 = [
            self::IS_PUBLIC,
            self::IS_PROTECTED,
            self::IS_PRIVATE,
            self::IS_PUBLIC | self::IS_PROTECTED,
        ];

        foreach ($cases3 as $modifier) {
            yield [
                ['foo', null, $modifier],
                fn (TestCase $test) => self::makeMethod($test, 'foo', $modifier),
                true,
            ];

            yield [
                ['foo', null, $modifier],
                fn (TestCase $test) => self::makeMethod($test, 'foo', self::VMASK & ~$modifier),
                false,
            ];
        }
    }

    /**
     * @dataProvider provMatches
     *
     * @param \Closure(TestCase):mixed $method
     */
    public function testMatches(array $args, \Closure $method, bool $expect): void
    {
        $spec = new MethodSpec(...$args);
        $this->assertSame($expect, $spec->matches($method($this)));
    }

    /**
     * @psalm-return array<array-key,array{
     *  0: array{0:non-empty-string, 1?:?bool, 2?:?int, 3?:?bool, 4?:?bool},
     *  1: string
     * }>
     */
    public static function provToString(): array
    {
        return [
            [
                ['foo'],
                'method foo',
            ],
            [
                ['foo', false],
                '!static method foo',
            ],
            [
                ['foo', true],
                'static method foo',
            ],
            [
                ['foo', null, self::IS_PUBLIC],
                'public method foo',
            ],
            [
                ['foo', null, self::IS_PROTECTED],
                'protected method foo',
            ],
            [
                ['foo', null, self::IS_PRIVATE],
                'private method foo',
            ],
            [
                ['foo', null, self::IS_PROTECTED | self::IS_PRIVATE],
                '!public method foo',
            ],
            [
                ['foo', null, self::IS_PUBLIC | self::IS_PRIVATE],
                '!protected method foo',
            ],
            [
                ['foo', null, self::IS_PUBLIC | self::IS_PROTECTED],
                '!private method foo',
            ],
            [
                ['foo', null, self::IS_PUBLIC | self::IS_PROTECTED | self::IS_PRIVATE],
                'method foo',
            ],
            [
                ['foo', true, self::IS_PUBLIC],
                'public static method foo',
            ],
            [
                ['foo', false, self::IS_PUBLIC],
                'public !static method foo',
            ],
            [
                ['foo', true, self::IS_PUBLIC, true],
                'abstract public static method foo',
            ],
            [
                ['foo', true, self::IS_PUBLIC, false],
                '!abstract public static method foo',
            ],
            [
                ['foo', true, self::IS_PUBLIC, null, true],
                'final public static method foo',
            ],
            [
                ['foo', true, self::IS_PUBLIC, null, false],
                '!final public static method foo',
            ],
        ];
    }

    /**
     * @dataProvider provToString
     */
    public function testToString(array $args, string $expect): void
    {
        $spec = new MethodSpec(...$args);
        $this->assertSame($expect, $spec->toString());
    }

    private static function makeMethod(TestCase $test, string $name, int $modifiers = self::IS_PUBLIC)
    {
        $stub = $test->getMockBuilder(\stdClass::class)
            ->addMethods([$name])
            ->getMock()
        ;
        $stub->expects($test->any())
            ->method($name)
        ;

        $method = $test->getMockBuilder(\ReflectionMethod::class)
            ->setConstructorArgs([$stub, $name])
            ->getMock()
        ;

        $method->expects($test->any())
            ->method('isStatic')
            ->willReturn(0 !== ($modifiers & self::IS_STATIC))
        ;

        $method->expects($test->any())
            ->method('isPublic')
            ->willReturn(0 !== ($modifiers & self::IS_PUBLIC))
        ;

        $method->expects($test->any())
            ->method('isProtected')
            ->willReturn(0 !== ($modifiers & self::IS_PROTECTED))
        ;

        $method->expects($test->any())
            ->method('isPrivate')
            ->willReturn(0 !== ($modifiers & self::IS_PRIVATE))
        ;

        $method->expects($test->any())
            ->method('isAbstract')
            ->willReturn(0 !== ($modifiers & self::IS_ABSTRACT))
        ;

        $method->expects($test->any())
            ->method('isFinal')
            ->willReturn(0 !== ($modifiers & self::IS_FINAL))
        ;

        $method->expects($test->any())
            ->method('getModifiers')
            ->willReturn($modifiers)
        ;

        return $method;
    }
}

// vim: syntax=php sw=4 ts=4 et:
