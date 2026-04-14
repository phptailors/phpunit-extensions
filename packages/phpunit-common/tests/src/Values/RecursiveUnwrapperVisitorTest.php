<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Values;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\CircularDependencyException;

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArgsEnter = array{node: array|ValuesInterface, path: list<array-key>}
 * @psalm-type ArgsVisit = array{node: mixed, path: list<array-key>, iterating: bool}
 */
#[CoversClass(RecursiveUnwrapperVisitor::class)]
final class RecursiveUnwrapperVisitorTest extends TestCase
{
    public const UNIQUE_TAG = RecursiveUnwrapperVisitor::UNIQUE_TAG;

    //
    //
    // TESTS
    //
    //

    public function testImplementsRecursiveVisitorInterface(): void
    {
        self::assertInstanceOf(RecursiveVisitorInterface::class, new RecursiveUnwrapperVisitor());
    }

    public function testInitialResult(): void
    {
        $visitor = new RecursiveUnwrapperVisitor();
        $this->assertSame([], $visitor->result());
    }

    /**
     * @return iterable<string, array{path: list<array-key>, expect: string}>
     */
    public static function provCycle(): iterable
    {
        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'path'   => [],
            'expect' => '',
        ];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'path'   => ['foo', 3, 'bar'],
            'expect' => "['foo'][3]['bar']",
        ];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'path'   => [null, 3, false],
            'expect' => '[NULL][3][false]',
        ];
    }

    /**
     * @param list<array-key> $path
     */
    #[DataProvider('provCycle')]
    public function testCycle(array $path, string $expect): void
    {
        $rePath = preg_quote($expect, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested values at \\\$values{$rePath}\\.$/");

        (new RecursiveUnwrapperVisitor())->cycle([], $path);
    }

    /**
     * @return iterable
     *
     * @psalm-return iterable<string, array{
     *      ctor: array,
     *      calls: non-empty-list<array{
     *          args: ArgsEnter,
     *          expect: mixed,
     *      }>,
     *      result: mixed
     *  }>
     */
    public static function provEnterLeave(): iterable
    {
        //
        // 01
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [],
            'calls' => [
                [
                    'args' => [
                        'node' => [],
                        'path' => [],
                    ],
                    'expect' => true,
                ],
            ],
            'result' => [],
        ];

        //
        // 02
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [],
            'calls' => [
                [
                    'args' => [
                        'node' => new ExpectedValues(),
                        'path' => [],
                    ],
                    'expect' => true,
                ],
            ],
            'result' => [self::UNIQUE_TAG => true],
        ];

        //
        // 03
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [],
            'calls' => [
                [
                    'args' => [
                        'node' => new ExpectedValues(),
                        'path' => [],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node' => [],
                        'path' => ['foo'],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node' => new ExpectedValues(),
                        'path' => ['foo', 'bar'],
                    ],
                    'expect' => true,
                ],
            ],
            'result' => [
                'foo' => [
                    'bar' => [
                        self::UNIQUE_TAG => true,
                    ],
                ],
                self::UNIQUE_TAG => true,
            ],
        ];

        //
        // 04
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [],
            'calls' => [
                [
                    'args' => [
                        'node' => new ExpectedValues(),
                        'path' => [],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node' => [],
                        'path' => ['foo'],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node' => new ActualValues(),
                        'path' => ['foo', 'bar'],
                    ],
                    'expect' => false,
                ],
            ],
            'result' => [
                'foo'            => [],
                self::UNIQUE_TAG => true,
            ],
        ];

        //
        // 05
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => [
                [
                    'args' => [
                        'node' => new ExpectedValues(),
                        'path' => [],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node' => [],
                        'path' => ['foo'],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node' => new ExpectedValues(),
                        'path' => ['foo', 'bar'],
                    ],
                    'expect' => true,
                ],
            ],
            'result' => [
                'foo' => [
                    'bar' => [],
                ],
            ],
        ];

        //
        // 06
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => [
                [
                    'args' => [
                        'node' => new ExpectedValues(),
                        'path' => [],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node' => [],
                        'path' => ['foo'],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node' => new ActualValues(),
                        'path' => ['foo', 'bar'],
                    ],
                    'expect' => false,
                ],
            ],
            'result' => [
                'foo' => [],
            ],
        ];
    }

    /**
     * @param array       $ctor
     * @param list<array> $calls
     * @param mixed       $result
     *
     * @psalm-param non-empty-list<array{args: EnterArgsT, expect: mixed}> $calls
     */
    #[DataProvider('provEnterLeave')]
    public function testEnterLeave(array $ctor, array $calls, $result): void
    {
        $visitor = new RecursiveUnwrapperVisitor(...$ctor);

        foreach ($calls as $call) {
            $args = $call['args'];
            $this->assertSame($call['expect'], $visitor->enter(...$args));
        }

        foreach (array_reverse($calls) as $call) {
            $args = array_merge($call['args'], ['iterating' => $call['expect']]);
            $this->assertNull($visitor->leave(...$args));
        }

        $this->assertSame($result, $visitor->result());
    }

    /**
     * @return iterable
     *
     * @psalm-return iterable<string, array{
     *      calls: non-empty-list<array{
     *          args: ArgsVisit,
     *      }>,
     *      result: mixed
     *  }>
     */
    public static function provVisit(): iterable
    {
        //
        // 01
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'calls' => [
                [
                    'args' => [
                        'node'      => [],
                        'path'      => [],
                        'iterating' => false,
                    ],
                ],
            ],
            'result' => [],
        ];

        //
        // 02
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'calls' => [
                [
                    'args' => [
                        'node'      => 'FOO',
                        'path'      => ['foo'],
                        'iterating' => false,
                    ],
                ],
                [
                    'args' => [
                        'node'      => 'BAR.GEZ',
                        'path'      => ['bar', 'gez'],
                        'iterating' => false,
                    ],
                ],
            ],
            'result' => [
                'foo' => 'FOO',
                'bar' => ['gez' => 'BAR.GEZ'],
            ],
        ];

        //
        // 02
        //

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'calls' => [
                [
                    'args' => [
                        'node'      => 'FOO',
                        'path'      => ['foo'],
                        'iterating' => false,
                    ],
                ],
                [
                    'args' => [
                        'node'      => 'FOO.BAR',
                        'path'      => ['foo', 'bar'],
                        'iterating' => false,
                    ],
                ],
            ],
            'result' => [
                'foo' => 'FOO',
            ],
        ];
    }

    /**
     * @param array $calls
     * @param mixed $result
     *
     * @psalm-param non-empty-list<array{args: ArgsVisit}> $calls
     */
    #[DataProvider('provVisit')]
    public function testVisit(array $calls, $result): void
    {
        $visitor = new RecursiveUnwrapperVisitor();

        foreach ($calls as $call) {
            $args = $call['args'];
            $this->assertNull($visitor->visit(...$args));
        }

        $this->assertSame($result, $visitor->result());
    }
}
// vim: syntax=php sw=4 ts=4 et:
