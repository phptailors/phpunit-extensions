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
 * @covers \Tailors\PHPUnit\Arrays\KsortedArrayExpectations
 * @covers \Tailors\PHPUnit\Arrays\KsortedArrayExpectationsTest
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
final class KsortedArrayExpectationsTest extends TestCase
{
    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testExtendsAbstractArraySpec(): void
    {
        $this->assertInstanceOf(AbstractArrayExpectations::class, new KsortedArrayExpectations([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsResultFactoryWrapperInterface(): void
    {
        $this->assertInstanceOf(ResultFactoryWrapperInterface::class, new KsortedArrayExpectations([]));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testImplementsComparatorAwareComplementInterface(): void
    {
        $this->assertInstanceOf(ComparatorAwareComplementInterface::class, new KsortedArrayExpectations([]));
    }

    /**
     * @psalm-return iterable<string, array{
     *      ctorArgs: CtorArgs,
     *      expect: array{array: mixed, flags?: mixed}
     * }>
     *
     * @codeCoverageIgnore
     */
    public static function provKsortedArrayExpectations(): iterable
    {
        yield basename(__FILE__).':'.__LINE__ => [
            'ctorArgs' => [[
                'a' => 'A',
            ]],

            'expect'   => [
                'array' => [
                    '_' => KsortedArrayExpectations::class,
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
                    '_' => KsortedArrayExpectations::class,
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
                    '_' => KsortedArrayExpectations::class,
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
                    '_' => KsortedArrayExpectations::class,
                    'c' => [
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
                    '_' => KsortedArrayExpectations::class,
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
                    '_' => KsortedArrayExpectations::class,
                    'c' => [
                        '_' => ArrayValuesSelection::class,
                        'e' => 'CE',
                        'd' => 'CD',
                        'f' => [
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
     * @dataProvider provKsortedArrayExpectations
     *
     * @psalm-param CtorArgs                           $ctorArgs
     * @psalm-param array{array: mixed, flags?: mixed} $expect
     *
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testKsortedArrayExpectations(array $ctorArgs, array $expect): void
    {
        $expectations = new KsortedArrayExpectations(...$ctorArgs);

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

                return is_array($node);
            };

        $this->assertSame($expect['array'], $mapper->map($expectations, $unwrapper));
    }

    /**
     * @psalm-suppress MissingThrowsDocblock
     */
    public function testComplement(): void
    {
        $expectations = new KsortedArrayExpectations([]);
        $comparator = new DummyComparator(false, 'similar to');

        $this->assertSame('an array similar to the specified one when ksorted', $expectations->complement($comparator));
    }
}
// vim: syntax=php sw=4 ts=4 et:
