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

/**
 * @small
 *
 * @internal This class is not covered by the backward compatibility promise
 *
 * @psalm-internal Tailors\PHPUnit
 *
 * @coversNothing
 */
#[CoversClass(RecursiveSelector::class)]
final class RecursiveSelectorTest extends TestCase
{
    public const UNIQUE_TAG = RecursiveUnwrapper::UNIQUE_TAG;

    public static function createSelection(...$args): SelectionInterface
    {
        return new DummySelection(new ArrayValueSelector(), false, ...$args);
    }

    public static function createSelectionWrapper(...$args): SelectionWrapperInterface
    {
        return new DummySelectionWrapper(self::createSelection(...$args));
    }

    //
    //
    // TESTS
    //
    //

    public function testImplementsRecursiveSelectorInterface(): void
    {
        $selection = $this->createMock(SelectionInterface::class);
        self::assertInstanceOf(RecursiveSelectorInterface::class, new RecursiveSelector($selection));
    }

    //
    // selectProperties()
    //

    public static function provSelect(): array
    {
        return [
            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => self::createSelection([]),
                'subject'   => [],
                'expect'    => [
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => self::createSelection([
                    'foo' => 'e:FOO',
                ]),
                'subject' => [
                    'foo' => 'a:FOO',
                ],
                'expect' => [
                    'foo'            => 'a:FOO',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => self::createSelection([
                    'foo' => 'e:FOO',
                    'bar' => [
                        'baz' => 'e:BAZ',
                    ],
                ]),
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz' => 'a:BAZ',
                    ],
                ],
                'expect' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz' => 'a:BAZ',
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => self::createSelection([
                    'foo' => 'e:FOO',
                    'bar' => [
                        'baz' => 'e:BAZ',
                    ],
                ]),
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => 'a:BAR',
                ],
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar'            => 'a:BAR',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => self::createSelection([
                    'foo' => 'e:FOO',
                    'bar' => self::createSelection([
                        'baz' => 'e:BAZ',
                    ]),
                ]),
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz' => 'a:BAZ',
                        'qux' => 'a:QUX',
                    ],
                ],
                'expect' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz'            => 'a:BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => self::createSelection([
                    'foo' => 'e:FOO',
                    'bar' => self::createSelectionWrapper([
                        'baz' => 'e:BAZ',
                    ]),
                ]),
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz' => 'a:BAZ',
                        'qux' => 'a:QUX',
                    ],
                ],
                'expect' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz'            => 'a:BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => self::createSelection([
                    'foo' => 'e:FOO',
                    'bar' => self::createSelection([
                        'baz' => 'e:BAZ',
                    ]),
                ]),
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => new \ArrayObject([
                        'baz' => 'a:BAZ',
                        'qux' => 'a:QUX',
                    ]),
                ],
                'expect' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz'            => 'a:BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => self::createSelection([
                    'foo' => 'e:FOO',
                    'bar' => self::createSelectionWrapper([
                        'baz' => 'e:BAZ',
                    ]),
                ]),
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => new \ArrayObject([
                        'baz' => 'a:BAZ',
                        'qux' => 'a:QUX',
                    ]),
                ],
                'expect' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz'            => 'a:BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => self::createSelection([
                    'foo' => 'e:FOO',
                    'bar' => self::createSelection([
                        'baz' => 'e:BAZ',
                    ]),
                ]),
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => 'a:BAR',
                ],
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar'            => 'a:BAR',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => self::createSelection([
                    'foo' => 'e:FOO',
                    'bar' => self::createSelectionWrapper([
                        'baz' => 'e:BAZ',
                    ]),
                ]),
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => 'a:BAR',
                ],
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar'            => 'a:BAR',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => self::createSelection([
                    'foo' => 'e:FOO',
                    'bar' => self::createSelection([
                        'baz' => 'e:BAZ',
                    ]),
                ]),
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => 'a:BAR',
                ],
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar'            => 'a:BAR',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursiveSelectorTest.php:'.__LINE__ => [
                'selection' => self::createSelection([
                    'foo' => 'e:FOO',
                    'bar' => self::createSelectionWrapper([
                        'baz' => 'e:BAZ',
                    ]),
                ]),
                'subject' => [
                    'foo' => 'a:FOO',
                    'bar' => 'a:BAR',
                ],
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar'            => 'a:BAR',
                    self::UNIQUE_TAG => true,
                ],
            ],
        ];
    }

    /**
     * @param SelectionInterface $selection
     * @param mixed              $subject
     */
    #[DataProvider('provSelect')]
    public function testSelect(SelectionInterface $selection, $subject, array $expect): void
    {
        $selector = new RecursiveSelector($selection);
        $unwrapper = new RecursiveUnwrapper();
        $selected = $selector->select($subject);
        self::assertInstanceOf(ActualValues::class, $selected);
        self::assertSame($expect, $unwrapper->unwrap($selected));
    }
}
// vim: syntax=php sw=4 ts=4 et:
