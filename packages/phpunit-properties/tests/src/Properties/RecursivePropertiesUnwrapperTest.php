<?php declare(strict_types=1);

/*
 * This file is part of php-tailors/phpunit-extensions.
 *
 * (c) Paweł Tomulik <ptomulik@meil.pw.edu.pl>
 *
 * View the LICENSE file for full copyright and license information.
 */

namespace Tailors\PHPUnit\Properties;

use PHPUnit\Framework\TestCase;
use Tailors\PHPUnit\ImplementsInterfaceTrait;

/**
 * @small
 * @covers \Tailors\PHPUnit\Properties\RecursivePropertiesUnwrapper
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursivePropertiesUnwrapperTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public const UNIQUE_TAG = RecursivePropertiesUnwrapper::UNIQUE_TAG;

    public function createExpectedProperties(...$args): ExpectedPropertiesInterface
    {
        $selector = $this->createMock(PropertySelectorInterface::class);

        return new ExpectedProperties($selector, ...$args);
    }

    public function createActualProperties(...$args): ActualPropertiesInterface
    {
        return new ActualProperties(...$args);
    }

    //
    //
    // TESTS
    //
    //

    public function testImplementsRecursivePropertiesUnwrapperInterface(): void
    {
        self::assertImplementsInterface(
            RecursivePropertiesUnwrapperInterface::class,
            RecursivePropertiesUnwrapper::class
        );
    }

    //
    // unwrap()
    //

    public function provUnwrap(): array
    {
        $actualProperties['[baz => BAZ]'] = $this->createActualProperties(['baz' => 'BAZ']);
        $expectProperties['[baz => BAZ]'] = $this->createExpectedProperties(['baz' => 'BAZ']);
        $arrayObject['[baz => BAZ]'] = new \ArrayObject(['baz' => 'BAZ']);

        return [
            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'args'       => [],
                'properties' => $this->createExpectedProperties([
                ]),
                'expect' => [
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'args'       => [],
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'args'       => [],
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => [
                        'baz' => 'BAZ',
                        'qux' => 'QUX',
                    ],
                ]),
                'expect' => [
                    'foo' => 'FOO',
                    'bar' => [
                        'baz' => 'BAZ',
                        'qux' => 'QUX',
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'args'       => [],
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $this->createExpectedProperties([
                        'baz' => 'BAZ',
                    ]),
                ]),
                'expect' => [
                    'foo' => 'FOO',
                    'bar' => [
                        'baz'            => 'BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'args'       => [],
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $this->createExpectedProperties([
                        'qux' => $this->createExpectedProperties(['baz' => 'BAZ']),
                        $this->createExpectedProperties(['fred' => 'FRED']),
                    ]),
                ]),
                'expect' => [
                    'foo' => 'FOO',
                    'bar' => [
                        'qux' => [
                            'baz'            => 'BAZ',
                            self::UNIQUE_TAG => true,
                        ],
                        0 => [
                            'fred'           => 'FRED',
                            self::UNIQUE_TAG => true,
                        ],
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'args'       => [],
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $actualProperties['[baz => BAZ]'],
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    'bar'            => $actualProperties['[baz => BAZ]'],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'args'       => [],
                'properties' => $this->createActualProperties([
                    'foo' => 'FOO',
                    'bar' => $expectProperties['[baz => BAZ]'],
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    'bar'            => $expectProperties['[baz => BAZ]'],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'args'       => [],
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $arrayObject['[baz => BAZ]'],
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    'bar'            => $arrayObject['[baz => BAZ]'],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'args'       => [],
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $arrayObject['[baz => BAZ]'],
                ]),
                'expect' => [
                    'foo'            => 'FOO',
                    'bar'            => $arrayObject['[baz => BAZ]'],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'args'       => [false], // no tagging
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                ]),
                'expect' => [
                    'foo' => 'FOO',
                ],
            ],

            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'args'       => [false], // no tagging
                'properties' => $this->createExpectedProperties([
                    'foo' => 'FOO',
                    'bar' => $this->createExpectedProperties([
                        'baz' => 'BAZ',
                    ]),
                ]),
                'expect' => [
                    'foo' => 'FOO',
                    'bar' => [
                        'baz' => 'BAZ',
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provUnwrap
     */
    public function testUnwrap(array $args, PropertiesInterface $properties, array $expect): void
    {
        $unwrapper = new RecursivePropertiesUnwrapper(...$args);
        self::assertSame($expect, $unwrapper->unwrap($properties));
    }

    public function provUnwrapThrowsExceptionOnCircularDependency(): array
    {
        $properties['#0'] = $this->createActualProperties([
            'foo' => [
            ],
        ]);
        $properties['#0']['foo']['bar'] = $properties['#0'];

        $properties['#1'] = $this->createActualProperties([
            'foo' => [
                'bar' => [
                ],
            ],
        ]);
        $properties['#1']['foo']['bar']['baz'] = $properties['#1'];

        $properties['#2'] = $this->createActualProperties([
            'foo' => [
                'bar' => $this->createActualProperties([
                    'baz' => 'BAZ',
                ]),
            ],
        ]);
        $properties['#2']['foo']['bar']['qux'] = $properties['#2']['foo']['bar'];

        $properties['#3'] = $this->createActualProperties([
            'foo' => [
                'bar' => $this->createActualProperties([]),
                'baz' => $this->createActualProperties([]),
            ],
        ]);
        $properties['#3']['foo']['bar']['qux'] = $properties['#3']['foo']['baz'];
        $properties['#3']['foo']['baz']['fred'] = $properties['#3']['foo']['bar'];

        return [
            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'properties' => $properties['#0'],
                'key'        => 'bar',
            ],

            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'properties' => $properties['#1'],
                'key'        => 'baz',
            ],

            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'properties' => $properties['#2'],
                'key'        => 'qux',
            ],

            'RecursivePropertiesUnwrapperTest.php:'.__LINE__ => [
                'properties' => $properties['#3'],
                'key'        => 'fred',
            ],
        ];
    }

    /**
     * @dataProvider provUnwrapThrowsExceptionOnCircularDependency
     *
     * @param mixed $key
     */
    public function testUnwrapThrowsExceptionOnCircularDependency(PropertiesInterface $properties, $key): void
    {
        $id = is_string($key) ? "'".addslashes($key)."'" : $key;
        $id = preg_quote($id, '/');
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessageMatches("/^Circular dependency found in nested properties at key {$id}\\.$/");

        (new RecursivePropertiesUnwrapper())->unwrap($properties);
    }
}
// vim: syntax=php sw=4 ts=4 et:
