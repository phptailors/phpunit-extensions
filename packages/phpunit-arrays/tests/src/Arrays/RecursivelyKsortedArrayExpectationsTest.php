<?php declare(strict_types=1);

/*
 * This file is part of phptailors/phpunit-extensions.
 *
 * Copyright (c) Paweł Tomulik <pawel@tomulik.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Arrays;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\Common\AbstractArrayExpectations;
use Tailors\PHPUnit\Common\TypesInterface;
use Tailors\PHPUnit\Comparator\DummyComparator;
use Tailors\PHPUnit\Predicate\ComparatorAwareComplementInterface;
use Tailors\PHPUnit\RecursiveMapper\RecursiveMapper;
use Tailors\PHPUnit\ResultFactory\ResultFactoryWrapperInterface;

/**
 * @small
 *
 * @covers \Tailors\PHPUnit\Arrays\RecursivelyKsortedArrayExpectations
 * @covers \Tailors\PHPUnit\Arrays\RecursivelyKsortedArrayExpectationsTest
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @psalm-import-type ArrayLike from TypesInterface
 *
 * @psalm-type CtorArgs = list{0: ArrayLike, 1?: int}
 *
 * @var TypesInterface $__phpactor__workaround__unused_import__TypesInterface
 */
final class RecursivelyKsortedArrayExpectationsTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testCreateReturnsAnInstance(): void
    {
        $this->assertInstanceOf(RecursivelyKsortedArrayExpectations::class, RecursivelyKsortedArrayExpectations::create([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractArraySpec(): void
    {
        $this->assertInstanceOf(AbstractArrayExpectations::class, RecursivelyKsortedArrayExpectations::create([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultFactoryWrapperInterface(): void
    {
        $this->assertInstanceOf(ResultFactoryWrapperInterface::class, RecursivelyKsortedArrayExpectations::create([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsComparatorAwareComplementInterface(): void
    {
        $this->assertInstanceOf(ComparatorAwareComplementInterface::class, RecursivelyKsortedArrayExpectations::create([]));
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      expect: array{array: mixed, flags?:mixed}
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provRecursivelyKsortedArrayExpectations(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [[
                'a' => 'A',
            ]],

            'expect'   => [
                'array' => [
                    '_' => RecursivelyKsortedArrayExpectations::class,
                    'a' => 'A',
                ],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [[
                'a' => 'A',
            ], SORT_NUMERIC],

            'expect'   => [
                'array' => [
                    '_' => RecursivelyKsortedArrayExpectations::class,
                    'a' => 'A',
                ],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [
                new \ArrayObject(['a' => 'A']),
            ],

            'expect'   => [
                'array' => [
                    '_' => RecursivelyKsortedArrayExpectations::class,
                    'a' => 'A',
                ],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [[
                'c' => [
                    'e' => 'E',
                    'd' => 'D',
                ],
                'b' => 'B',
                'a' => 'A',
            ]],

            'expect'   => [
                'array' => [
                    '_' => RecursivelyKsortedArrayExpectations::class,
                    'c' => [
                        '_' => KsortedArrayExpectations::class,
                        'e' => 'E',
                        'd' => 'D',
                    ],
                    'b' => 'B',
                    'a' => 'A',
                ],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [[
                'c' => new \ArrayObject([
                    'e' => 'CE',
                    'd' => 'CD',
                ]),
                'b' => 'B',
                'a' => 'A',
            ]],

            'expect'   => [
                'array' => [
                    '_' => RecursivelyKsortedArrayExpectations::class,
                    'c' => [
                        '_' => \ArrayObject::class,
                        'e' => 'CE',
                        'd' => 'CD',
                    ],
                    'b' => 'B',
                    'a' => 'A',
                ],
            ],
        ];

        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [[
                'c' => new ArrayValuesSelection([
                    'e' => 'CE',
                    'd' => 'CD',
                    'f' => [
                        'h' => 'CFH',
                        'g' => 'CFG',
                    ],
                ]),
                'b' => 'B',
                'a' => 'A',
            ]],

            'expect'   => [
                'array' => [
                    '_' => RecursivelyKsortedArrayExpectations::class,
                    'c' => [
                        '_' => ArrayValuesSelection::class,
                        'e' => 'CE',
                        'd' => 'CD',
                        'f' => [
                            '_' => KsortedArrayExpectations::class,
                            'h' => 'CFH',
                            'g' => 'CFG',
                        ],
                    ],
                    'b' => 'B',
                    'a' => 'A',
                ],
            ],
        ];
    }

    /**
     * @dataProvider provRecursivelyKsortedArrayExpectations
     *
     * @psalm-param CtorArgs                          $ctorArgs
     * @psalm-param array{array: mixed, flags?:mixed} $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testRecursivelyKsortedArrayExpectations(array $ctorArgs, array $expect): void
    {
        $expectations = RecursivelyKsortedArrayExpectations::create(...$ctorArgs);

        $mapper = RecursiveMapper::create();
        $unwrapper =
            /**
             * @psalm-param ArrayLike $node
             */
            function (iterable &$node): bool {
                if ($node instanceof \ArrayAccess) {
                    $node = ['_' => get_class($node)];

                    return true;
                }

                // @codeCoverageIgnoreStart
                return is_array($node);
                // @codeCoverageIgnoreEnd
            };

        $this->assertSame($expect['array'], $mapper->map($expectations, $unwrapper));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testItDoesNotAlterPassedInObjects(): void
    {
        $arr = [
            'd' => [
                'f' => 'DF',
                'e' => 'DE',
            ],
            'c' => 'C',
        ];

        $arrObj = new \ArrayObject($arr);

        $_expectations = RecursivelyKsortedArrayExpectations::create([
            'a' => $arrObj,
            'b' => 'B',
        ]);

        self::assertSame($arr, (array) $arrObj);
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testComplement(): void
    {
        $expectations = RecursivelyKsortedArrayExpectations::create([]);
        $comparator = new DummyComparator(false, 'similar to');

        $this->assertSame('an array similar to the specified one when ksorted recursively', $expectations->complement($comparator));
    }
}
// vim: syntax=php sw=4 ts=4 et:
