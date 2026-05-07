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
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\CircularDependencyException;

/**
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-type ArgsEnter = array{node: array|ValuesInterface, path: list<array-key>, stack: list<array|ValuesInterface>}
 * @psalm-type ArgsVisit = array{node: mixed, path: list<array-key>, stack: list<array|ValuesInterface>, iterating: bool}
 */
#[CoversClass(RecursiveUnwrapperVisitor::class)]
#[Small]
final class RecursiveUnwrapperVisitorTest extends TestCase
{
    public const string UNIQUE_TAG = RecursiveUnwrapperVisitor::UNIQUE_TAG;

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
            'stack'  => [],
            'expect' => '',
        ];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'path'   => ['foo', 3, 'bar'],
            'stack'  => [[], []],
            'expect' => "['foo'][3]['bar']",
        ];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'path'   => [null, 3, false],
            'stack'  => [[], []],
            'expect' => '[NULL][3][false]',
        ];
    }

    /**
     * @param list<array-key>             $path
     * @param list<array|ValuesInterface> $stack
     */
    #[DataProvider('provCycle')]
    public function testCycle(array $path, array $stack, string $expect): void
    {
        $rePath = preg_quote($expect, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested values at \\\$values{$rePath}\\.$/");

        new RecursiveUnwrapperVisitor()->cycle([], $path, $stack);
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
                        'node'  => [],
                        'path'  => [],
                        'stack' => [],
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
                        'node'  => new ExpectedValues(),
                        'path'  => [],
                        'stack' => [],
                    ],
                    'expect' => true,
                ],
            ],
            'result' => [self::UNIQUE_TAG => true],
        ];

        //
        // 03
        //

        $s03 = [new ExpectedValues(), [], new ExpectedValues()];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [],
            'calls' => [
                [
                    'args' => [
                        'node'  => $s03[0],
                        'path'  => [],
                        'stack' => [],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $s03[1],
                        'path'  => ['foo'],
                        'stack' => [$s03[0]],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $s03[2],
                        'path'  => ['foo', 'bar'],
                        'stack' => [$s03[0], $s03[1]],
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
        $s04 = [new ExpectedValues(), [], new ActualValues()];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [],
            'calls' => [
                [
                    'args' => [
                        'node'  => $s04[0],
                        'path'  => [],
                        'stack' => [],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $s04[1],
                        'path'  => ['foo'],
                        'stack' => [$s04[0]],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $s04[2],
                        'path'  => ['foo', 'bar'],
                        'stack' => [$s04[0], $s04[1]],
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
        $s05 = [new ExpectedValues(), [], new ExpectedValues()];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => [
                [
                    'args' => [
                        'node'  => $s05[0],
                        'path'  => [],
                        'stack' => [],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $s05[1],
                        'path'  => ['foo'],
                        'stack' => [$s05[0]],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $s05[2],
                        'path'  => ['foo', 'bar'],
                        'stack' => [$s05[0], $s05[1]],
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
        $s06 = [new ExpectedValues(), [], new ActualValues()];

        yield 'RecursiveUnwrapperVisitorTest.php:'.__LINE__ => [
            'ctor'  => [false],
            'calls' => [
                [
                    'args' => [
                        'node'  => $s06[0],
                        'path'  => [],
                        'stack' => [],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $s06[1],
                        'path'  => ['foo'],
                        'stack' => [$s06[0]],
                    ],
                    'expect' => true,
                ],
                [
                    'args' => [
                        'node'  => $s06[2],
                        'path'  => ['foo', 'bar'],
                        'stack' => [$s06[0], $s06[1]],
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
     *
     * @psalm-param non-empty-list<array{args: EnterArgsT, expect: mixed}> $calls
     */
    #[DataProvider('provEnterLeave')]
    public function testEnterLeave(array $ctor, array $calls, mixed $result): void
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
                        'stack'     => [],
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
                        'stack'     => [],
                        'iterating' => false,
                    ],
                ],
                [
                    'args' => [
                        'node'      => 'BAR.GEZ',
                        'path'      => ['bar', 'gez'],
                        'stack'     => [[]],
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
                        'stack'     => [],
                        'iterating' => false,
                    ],
                ],
                [
                    'args' => [
                        'node'      => 'FOO.BAR',
                        'path'      => ['foo', 'bar'],
                        'stack'     => [[]],
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
     *
     * @psalm-param non-empty-list<array{args: ArgsVisit}> $calls
     */
    #[DataProvider('provVisit')]
    public function testVisit(array $calls, mixed $result): void
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
