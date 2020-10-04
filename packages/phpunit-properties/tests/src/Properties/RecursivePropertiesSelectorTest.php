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
 * @covers \Tailors\PHPUnit\Properties\RecursivePropertiesSelector
 *
 * @internal This class is not covered by the backward compatibility promise
 * @psalm-internal Tailors\PHPUnit
 */
final class RecursivePropertiesSelectorTest extends TestCase
{
    use ImplementsInterfaceTrait;

    public const UNIQUE_TAG = RecursivePropertiesUnwrapper::UNIQUE_TAG;

    public static function createExpectedObjectProperties(...$args): ExpectedProperties
    {
        return new ExpectedProperties(new ObjectPropertySelector(), ...$args);
    }

    public static function createExpectedClassProperties(...$args): ExpectedProperties
    {
        return new ExpectedProperties(new ClassPropertySelector(), ...$args);
    }

    //
    //
    // TESTS
    //
    //

    public function testImplementsRecursivePropertiesSelectorInterface(): void
    {
        self::assertImplementsInterface(RecursivePropertiesSelectorInterface::class, RecursivePropertiesSelector::class);
    }

    //
    // unwrap()
    //

    public static function provSelectProperties(): array
    {
        $actAct = new ActualProperties([
            'act' => 'x:ACT',
        ]);

        $expExp = new ExpectedProperties(new ObjectPropertySelector(), [
            'exp' => 'x:EXP',
        ]);

        $objBazBaz = new class() {
            public $baz = 'a:BAZ';
        };

        $clsBazBaz = get_class($objBazBaz);

        return [
            'RecursivePropertiesSelectorTest.php:'.__LINE__ => [
                'selector' => static::createExpectedObjectProperties([]),
                'subject'  => new class() {
                },
                'expect' => [
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesSelectorTest.php:'.__LINE__ => [
                'selector' => static::createExpectedObjectProperties([
                    'foo' => 'e:FOO',
                ]),
                'subject' => new class() {
                    public $foo = 'a:FOO';
                },
                'expect' => [
                    'foo'            => 'a:FOO',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesSelectorTest.php:'.__LINE__ => [
                'selector' => static::createExpectedObjectProperties([
                    'foo' => 'e:FOO',
                    'bar' => [
                        'baz' => 'e:BAZ',
                    ],
                ]),
                'subject' => new class() {
                    public $foo = 'a:FOO';
                    public $bar = [
                        'baz' => 'a:BAZ',
                    ];
                },
                'expect' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz' => 'a:BAZ',
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesSelectorTest.php:'.__LINE__ => [
                'selector' => static::createExpectedObjectProperties([
                    'foo' => 'e:FOO',
                    'bar' => [
                        'baz' => 'e:BAZ',
                    ],
                ]),
                'subject' => new class() {
                    public $foo = 'a:FOO';
                    public $bar = 'a:BAR';
                },
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar'            => 'a:BAR',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesSelectorTest.php:'.__LINE__ => [
                'selector' => static::createExpectedObjectProperties([
                    'foo' => 'e:FOO',
                    'bar' => static::createExpectedObjectProperties([
                        'baz' => 'e:BAZ',
                    ]),
                ]),
                'subject' => new class() {
                    public $foo = 'a:FOO';
                    public $bar;

                    public function __construct()
                    {
                        $this->bar = new class() {
                            public $baz = 'a:BAZ';
                        };
                    }
                },
                'expect' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'baz'            => 'a:BAZ',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesSelectorTest.php:'.__LINE__ => [
                'selector' => static::createExpectedObjectProperties([
                    'foo' => 'e:FOO',
                    'bar' => 'e:BAR',
                ]),
                'subject' => new class($actAct) {
                    public $foo = 'a:FOO';
                    public $bar;

                    public function __construct($bar)
                    {
                        $this->bar = $bar;
                    }
                },
                'expect' => [
                    'foo' => 'a:FOO',
                    'bar' => [
                        'act'            => 'x:ACT',
                        self::UNIQUE_TAG => true,
                    ],
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesSelectorTest.php:'.__LINE__ => [
                'selector' => static::createExpectedObjectProperties([
                    'foo' => 'e:FOO',
                    'bar' => 'e:BAR',
                ]),
                'subject' => new class($expExp) {
                    public $foo = 'a:FOO';
                    public $bar;

                    public function __construct($bar)
                    {
                        $this->bar = $bar;
                    }
                },
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar'            => $expExp,
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesSelectorTest.php:'.__LINE__ => [
                'selector' => static::createExpectedClassProperties([]),
                'subject'  => new class() {
                },
                'expect' => [
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesSelectorTest.php:'.__LINE__ => [
                'selector' => static::createExpectedClassProperties([
                    'foo' => 'e:FOO',
                ]),
                'subject' => get_class(new class() {
                    public static $foo = 'a:FOO';
                }),
                'expect' => [
                    'foo'            => 'a:FOO',
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesSelectorTest.php:'.__LINE__ => [
                'selector' => static::createExpectedObjectProperties([
                    'foo'   => 'e:FOO',
                    'bar()' => static::createExpectedClassProperties([
                        'baz' => 'e:BAZ',
                    ]),
                ]),
                'subject' => new class($objBazBaz) {
                    public $foo = 'a:FOO';
                    private $bar;

                    public function __construct(object $bar)
                    {
                        $this->bar = $bar;
                    }

                    public function bar(): object
                    {
                        return $this->bar;
                    }
                },
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar()'          => $objBazBaz,
                    self::UNIQUE_TAG => true,
                ],
            ],

            'RecursivePropertiesSelectorTest.php:'.__LINE__ => [
                'selector' => static::createExpectedObjectProperties([
                    'foo'   => 'e:FOO',
                    'bar()' => static::createExpectedObjectProperties([
                        'baz' => 'e:BAZ',
                    ]),
                ]),
                'subject' => new class($clsBazBaz) {
                    public $foo = 'a:FOO';
                    private $bar;

                    public function __construct(string $bar)
                    {
                        $this->bar = $bar;
                    }

                    public function bar(): string
                    {
                        return $this->bar;
                    }
                },
                'expect' => [
                    'foo'            => 'a:FOO',
                    'bar()'          => $clsBazBaz,
                    self::UNIQUE_TAG => true,
                ],
            ],
        ];
    }

    /**
     * @dataProvider provSelectProperties
     *
     * @param mixed $subject
     */
    public function testSelectProperties(ExpectedProperties $selector, $subject, array $expect): void
    {
        $selector = new RecursivePropertiesSelector($selector);
        $unwrapper = new RecursivePropertiesUnwrapper();
        $selected = $selector->selectProperties($subject);
        self::assertInstanceOf(ActualProperties::class, $selected);
        self::assertSame($expect, $unwrapper->unwrap($selected));
    }
}
// vim: syntax=php sw=4 ts=4 et:
